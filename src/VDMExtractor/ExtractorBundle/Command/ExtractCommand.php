<?php

namespace VDMExtractor\ExtractorBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


/**
 * Class that will handle posts extraction command
 * Use : app/console extract:vdm <numberOfPosts>
 */
class ExtractCommand extends ContainerAwareCommand
{

	/**
     * {@inheritdoc}
     */
	protected function configure()
    {
        $this
            ->setName('extract:vdm')
            ->setDescription('Extract a specified number of posts from vdm.com')
            ->addArgument('limit', InputArgument::REQUIRED, 'How much do you wanna see???')
        ;
    }

    /**
     * {@inheritdoc}
     */
	protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getArgument('limit');
        if ($limit) {
            $text = 'Bonjour '.$limit;

            //Do Magic!!!
            // On récupère le service
    		$extractorService = $this->getContainer()->get('vdm_extractor.extractorservice');


        } else {
            $text = 'You\'re supposed to put a number at the end of the request';
        }

        $output->writeln($text);
    }

}