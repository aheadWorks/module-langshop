<?php
declare(strict_types=1);

namespace Aheadworks\Langshop\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Aheadworks\Langshop\Model\Mode\State as ModeState;

class SetMode extends Command
{
    /**
     * Input argument mode
     */
    private const MODE = 'mode';

    /**
     * @param ModeState $modeState
     */
    public function __construct(
        private readonly ModeState $modeState
    ) {
        parent::__construct();
    }

    /**
     * Configures the current command
     *
     * @return void
     */
    protected function configure(): void
    {
        $description = sprintf(
            'Rendering application mode - possible values ("%s", "%s")',
            ModeState::LANGSHOP_APP,
            ModeState::APP_BUILDER
        );
        $this->addArgument(self::MODE, InputArgument::REQUIRED, $description);
        $this
            ->setName('aw-langshop:set-mode')
            ->setDescription('Set application rendering mode');

        parent::configure();
    }

    /**
     * Executes the current command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mode = $input->getArgument(self::MODE);
        try {
            $this->modeState->setMode($mode);
            if ($mode === ModeState::LANGSHOP_APP) {
                $output->writeln('Enabled mode: Built-in Langshop application');
            }
            if ($mode === ModeState::APP_BUILDER) {
                $output->writeln('Enabled mode: Langshop application via App Builder ');
            }
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        return Cli::RETURN_SUCCESS;
    }
}
