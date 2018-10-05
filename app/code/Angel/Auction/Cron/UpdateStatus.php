<?php


namespace Angel\Auction\Cron;

class UpdateStatus
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Angel\Auction\Model\Auction
     */
    protected $auction;

    /**
     * UpdateStatus constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Angel\Auction\Model\Auction $auction
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Angel\Auction\Model\Auction $auction
    ){
        $this->logger = $logger;
        $this->auction = $auction;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
//        $this->logger->addInfo("Cronjob updateStatus is executed.");
        $this->auction->massUpdateStatus();
    }
}
