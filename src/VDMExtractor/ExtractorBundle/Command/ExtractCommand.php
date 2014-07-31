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
            ->addArgument('limit', InputArgument::OPTIONAL, 'How much do you wanna see???')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getArgument('limit');

        if (!$limit) {

            $limit = 200; 

        }

        // Do Magic!!!
        // On recupere le service et on lance l'extraction
        $extractorService = $this->getContainer()->get('vdm_extractor.extractorservice');
        $extractorService->extract($limit);

        $text  = 'Yay we have just extracted ' .$limit .' vdms';

        $output->writeln($text);
    }

}