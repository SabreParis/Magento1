<?php

/**
 * created: 2015
 *
 * @category  Ayaline
 * @package   Ayaline_XXXX
 * @author    aYaline
 * @copyright Ayaline - 2015 - http://magento-shop.ayaline.com
 * @license   http://shop.ayaline.com/magento/fr/conditions-generales-de-vente.html
 */

namespace Ayaline\EnhancedAdmin;

use N98\Magento\Command\AbstractMagentoCommand;
use N98\Util\String;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class DumpConfigCommand extends AbstractMagentoCommand
{

    /**
     * @var \Mage_Adminhtml_Model_Config
     */
    protected $adminhtmlConfig;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected $paths = array();

    protected $filename = 'dump_config.php.dump';

    private function sortNode($a, $b)
    {
        return (int)$a->sort_order < (int)$b->sort_order ? -1 : ((int)$a->sort_order > (int)$b->sort_order ? 1 : 0);
    }

    protected function configure()
    {
        $this
            ->setName('ayaline:enhanced-admin:dump-config')
            ->addArgument('section', InputArgument::OPTIONAL, 'Export section (Multiple codes separated by comma)')
            ->setDescription('Export Configuration (for setup)');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->detectMagento($output);
        if ($this->initMagento()) {
            $this->adminhtmlConfig = \Mage::getSingleton('adminhtml/config');
            $this->input = $input;
            $this->output = $output;

            $this->extractConfig();
            $this->write();
        }
    }

    protected function extractConfig()
    {
        $tabs = (array)$this->adminhtmlConfig->getTabs()->children();
        $sections = (array)$this->adminhtmlConfig->getSections();

        if ($argSection = $this->input->getArgument('section')) {
            $argSection = String::trimExplodeEmpty(',', $argSection);

            $selectedSections = array();

            foreach ($argSection as $_argSection) {
                if (array_key_exists($_argSection, $sections)) {
                    $selectedSections[] = $sections[$_argSection];
                } else {
                    $this->output->writeln("<info>Can't find section {$_argSection}</info>");
                }
            }

            $sections = $selectedSections;
        }

        uasort($sections, array($this, 'sortNode'));
        uasort($tabs, array($this, 'sortNode'));

        foreach ($tabs as $_tab) {
            $this->paths[$_tab->getName()] = [];
        }

        foreach ($sections as $_section) {

            if ($this->adminhtmlConfig->hasChildren($_section)) {

                $_groups = (array)$_section->groups;
                uasort($_groups, array($this, 'sortNode'));

                foreach ($_groups as $_group) {

                    if ($this->adminhtmlConfig->hasChildren($_group)) {

                        $_fields = (array)$_group->fields;
                        uasort($_fields, array($this, 'sortNode'));

                        foreach ($_fields as $_field) {

                            $this->paths[$_section->tab->__toString()]["{$_section->getName()}/{$_group->getName()}/{$_field->getName()}"] = [
                                'label'           => "{$_section->label->__toString()} > {$_group->label->__toString()} > {$_field->label->__toString()}",
                                'show_in_store'   => $_field->show_in_store->__toString(),
                                'show_in_website' => $_field->show_in_website->__toString(),
                                'show_in_default' => $_field->show_in_default->__toString(),
                                'value_type'      => $_field->frontend_type->__toString(),
                                'value_source'    => $_field->source_model ? $_field->source_model->__toString() : '',
                                'value_default'   => \Mage::getConfig()->getNode("default/{$_section->getName()}/{$_group->getName()}/{$_field->getName()}"),
                            ];

                        }

                    }

                }

            }

        }

    }

    protected function write()
    {
        $dump = '<?php' . PHP_EOL . PHP_EOL;
        $thisStr = '$this';
        // generate setup config
        foreach ($this->paths as $_tab => $_paths) {

            if (empty($_paths)) {
                continue;
            }

            $_tabTxt = "#####     {$_tab}     #####";
            $_tabSep = str_repeat('#', strlen($_tabTxt));

            $dump .= $_tabSep . PHP_EOL;
            $dump .= $_tabTxt . PHP_EOL;
            $dump .= $_tabSep . PHP_EOL . PHP_EOL;

            $this->output->writeln(PHP_EOL . "<info>Tab {$_tab}</info>");

            foreach ($_paths as $_path => $_info) {

                $this->output->writeln("\t<info>{$_info['label']} <comment>({$_path})</comment></info>");

                $_row = '/**' . PHP_EOL;
                $_row .= " * {$_info['label']}" . PHP_EOL;

                if ($_info['value_source']) {
                    $_row .= " *  Source: {$_info['value_source']}" . PHP_EOL;
                }

                $_row .= " *  Default: {$_info['value_default']}" . PHP_EOL;

                if ($_info['show_in_store'] || $_info['show_in_website']) {
                    $_row .= ' *' . PHP_EOL;
                }

                $_canOrMust = ($_info['show_in_store']) ? 'Can' : 'Must';

                if ($_info['show_in_store']) {
                    $_row .= " *   {$_canOrMust} be configured on store view" . PHP_EOL;
                }

                if ($_info['show_in_website']) {
                    $_row .= " *   {$_canOrMust} be configured on website view" . PHP_EOL;
                }

                $_row .= ' */' . PHP_EOL;

                $_row .= "{$thisStr}->setConfigData('{$_path}', 'VALUE_{$_info['value_type']}');" . PHP_EOL;

                $dump .= $_row . PHP_EOL;
            }

        }

        if (\file_put_contents($this->_magentoRootFolder . DS . $this->filename, $dump, LOCK_EX)) {
            $this->output->writeln('');
            $this->output->writeln("<info>File <comment>{$this->filename}</comment> generated</info>");
            $this->output->writeln('');
            $this->output->writeln("<error>Get it and remove it!</error>");
            $this->output->writeln('');
        }

    }

}
