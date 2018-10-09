<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Auction\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * @api
 * @since 100.1.0
 */
interface EmailNotificationInterface{
    const NEW_BID = 'auction_email_new_bid';
    const AUCTION_FINISHED = 'auction_email_auction_finished';
    const AUCTION_CANCELED = 'auction_email_auction_canceled';
    const OVER_BID = 'auction_email_over_bid';

    public function sentNewBidNotificatuon($bid);

    public function sentAuctionFinishedNotification($product, $email);

    public function sentAuctionCaceledNotification($product);

    public function sentOverBidNotifycation($lastBid, $bid);
}
