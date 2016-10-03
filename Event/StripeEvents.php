<?php

namespace WarbleMedia\PhoenixBundle\Event;

final class StripeEvents
{
    /**
     * The ALL event occurs when any event is captured.
     *
     * This event is dispatched before the specified event is dispatched.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ALL = 'warble_media_phoenix.webhook.stripe';

    /**
     * The ACCOUNT_UPDATED event occurs whenever an account status or property has changed.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ACCOUNT_UPDATED = 'warble_media_phoenix.webhook.stripe.account.updated';

    /**
     * The ACCOUNT_APPLICATION_DEAUTHORIZED event occurs whenever a user deauthorizes an application. Sent to the
     * related application only.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ACCOUNT_APPLICATION_DEAUTHORIZED = 'warble_media_phoenix.webhook.stripe.account.application.deauthorized';

    /**
     * The ACCOUNT_EXTERNAL_ACCOUNT_CREATED event occurs whenever an external account is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ACCOUNT_EXTERNAL_ACCOUNT_CREATED = 'warble_media_phoenix.webhook.stripe.account.external_account.created';

    /**
     * The ACCOUNT_EXTERNAL_ACCOUNT_DELETED event occurs whenever an external account is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ACCOUNT_EXTERNAL_ACCOUNT_DELETED = 'warble_media_phoenix.webhook.stripe.account.external_account.deleted';

    /**
     * The ACCOUNT_EXTERNAL_ACCOUNT_UPDATED event occurs whenever an external account is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ACCOUNT_EXTERNAL_ACCOUNT_UPDATED = 'warble_media_phoenix.webhook.stripe.account.external_account.updated';

    /**
     * The APPLICATION_FEE_CREATED event occurs whenever an application fee is created on a charge.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const APPLICATION_FEE_CREATED = 'warble_media_phoenix.webhook.stripe.application_fee.created';

    /**
     * The APPLICATION_FEE_REFUNDED event occurs whenever an application fee is refunded, whether from refunding a
     * charge or from refunding the application fee directly, including partial refunds.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const APPLICATION_FEE_REFUNDED = 'warble_media_phoenix.webhook.stripe.application_fee.refunded';

    /**
     * The APPLICATION_FEE_REFUND_UPDATED event occurs whenever an application fee refund is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const APPLICATION_FEE_REFUND_UPDATED = 'warble_media_phoenix.webhook.stripe.application_fee.refund.updated';

    /**
     * The BALANCE_AVAILABLE event occurs whenever your Stripe balance has been updated (e.g. when a charge collected
     * is available to be paid out). By default, Stripe will automatically transfer any funds in your balance to your
     * bank account on a daily basis.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const BALANCE_AVAILABLE = 'warble_media_phoenix.webhook.stripe.balance.available';

    /**
     * The BITCOIN_RECEIVER_CREATED event occurs whenever a receiver has been created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const BITCOIN_RECEIVER_CREATED = 'warble_media_phoenix.webhook.stripe.bitcoin.receiver.created';

    /**
     * The BITCOIN_RECEIVER_FILLED event occurs whenever a receiver is filled (that is, when it has received enough
     * bitcoin to process a payment of the same amount).
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const BITCOIN_RECEIVER_FILLED = 'warble_media_phoenix.webhook.stripe.bitcoin.receiver.filled';

    /**
     * The BITCOIN_RECEIVER_UPDATED event occurs whenever a receiver is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const BITCOIN_RECEIVER_UPDATED = 'warble_media_phoenix.webhook.stripe.bitcoin.receiver.updated';

    /**
     * The BITCOIN_RECEIVER_TRANSACTION_CREATED event occurs whenever bitcoin is pushed to a receiver.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const BITCOIN_RECEIVER_TRANSACTION_CREATED = 'warble_media_phoenix.webhook.stripe.bitcoin.receiver.transaction.created';

    /**
     * The CHARGE_CAPTURED event occurs whenever a previously uncaptured charge is captured.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_CAPTURED = 'warble_media_phoenix.webhook.stripe.charge.captured';

    /**
     * The CHARGE_FAILED event occurs whenever a failed charge attempt occurs.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_FAILED = 'warble_media_phoenix.webhook.stripe.charge.failed';

    /**
     * The CHARGE_PENDING event occurs whenever a pending charge is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_PENDING = 'warble_media_phoenix.webhook.stripe.charge.pending';

    /**
     * The CHARGE_REFUNDED event occurs whenever a charge is refunded, including partial refunds.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_REFUNDED = 'warble_media_phoenix.webhook.stripe.charge.refunded';

    /**
     * The CHARGE_SUCCEEDED event occurs whenever a new charge is created and is successful.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_SUCCEEDED = 'warble_media_phoenix.webhook.stripe.charge.succeeded';

    /**
     * The CHARGE_UPDATED event occurs whenever a charge description or metadata is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_UPDATED = 'warble_media_phoenix.webhook.stripe.charge.updated';

    /**
     * The CHARGE_DISPUTE_CLOSED event occurs when the dispute is closed and the dispute status changes to
     * charge_refunded, lost, warning_closed, or won.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_DISPUTE_CLOSED = 'warble_media_phoenix.webhook.stripe.charge.dispute.closed';

    /**
     * The CHARGE_DISPUTE_CREATED event occurs whenever a customer disputes a charge with their bank (chargeback).
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_DISPUTE_CREATED = 'warble_media_phoenix.webhook.stripe.charge.dispute.created';

    /**
     * The CHARGE_DISPUTE_FUNDS_REINSTATED event occurs when funds are reinstated to your account after a dispute
     * is won.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_DISPUTE_FUNDS_REINSTATED = 'warble_media_phoenix.webhook.stripe.charge.dispute.funds_reinstated';

    /**
     * The CHARGE_DISPUTE_FUNDS_WITHDRAWN event occurs when funds are removed from your account due to a dispute.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_DISPUTE_FUNDS_WITHDRAWN = 'warble_media_phoenix.webhook.stripe.charge.dispute.funds_withdrawn';

    /**
     * The CHARGE_DISPUTE_UPDATED event occurs when the dispute is updated (usually with evidence).
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CHARGE_DISPUTE_UPDATED = 'warble_media_phoenix.webhook.stripe.charge.dispute.updated';

    /**
     * The COUPON_CREATED event occurs whenever a coupon is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const COUPON_CREATED = 'warble_media_phoenix.webhook.stripe.coupon.created';

    /**
     * The COUPON_DELETED event occurs whenever a coupon is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const COUPON_DELETED = 'warble_media_phoenix.webhook.stripe.coupon.deleted';

    /**
     * The COUPON_UPDATED event occurs whenever a coupon is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const COUPON_UPDATED = 'warble_media_phoenix.webhook.stripe.coupon.updated';

    /**
     * The CUSTOMER_CREATED event occurs whenever a new customer is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_CREATED = 'warble_media_phoenix.webhook.stripe.customer.created';

    /**
     * The CUSTOMER_DELETED event occurs whenever a customer is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_DELETED = 'warble_media_phoenix.webhook.stripe.customer.deleted';

    /**
     * The CUSTOMER_UPDATED event occurs whenever any property of a customer changes.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_UPDATED = 'warble_media_phoenix.webhook.stripe.customer.updated';

    /**
     * The CUSTOMER_DISCOUNT_CREATED event occurs whenever a coupon is attached to a customer.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_DISCOUNT_CREATED = 'warble_media_phoenix.webhook.stripe.customer.discount.created';

    /**
     * The CUSTOMER_DISCOUNT_DELETED event occurs whenever a customer's discount is removed.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_DISCOUNT_DELETED = 'warble_media_phoenix.webhook.stripe.customer.discount.deleted';

    /**
     * The CUSTOMER_DISCOUNT_UPDATED event occurs whenever a customer is switched from one coupon to another.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_DISCOUNT_UPDATED = 'warble_media_phoenix.webhook.stripe.customer.discount.updated';

    /**
     * The CUSTOMER_SOURCE_CREATED event occurs whenever a new source is created for the customer.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SOURCE_CREATED = 'warble_media_phoenix.webhook.stripe.customer.source.created';

    /**
     * The CUSTOMER_SOURCE_DELETED event occurs whenever a source is removed from a customer.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SOURCE_DELETED = 'warble_media_phoenix.webhook.stripe.customer.source.deleted';

    /**
     * The CUSTOMER_SOURCE_UPDATED event occurs whenever a source's details are changed.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SOURCE_UPDATED = 'warble_media_phoenix.webhook.stripe.customer.source.updated';

    /**
     * The CUSTOMER_SUBSCRIPTION_CREATED event occurs whenever a customer with no subscription is signed up for a plan.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SUBSCRIPTION_CREATED = 'warble_media_phoenix.webhook.stripe.customer.subscription.created';

    /**
     * The CUSTOMER_SUBSCRIPTION_DELETED event occurs whenever a customer ends their subscription.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SUBSCRIPTION_DELETED = 'warble_media_phoenix.webhook.stripe.customer.subscription.deleted';

    /**
     * The CUSTOMER_SUBSCRIPTION_TRIAL_WILL_END event occurs three days before the trial period of a subscription
     * is scheduled to end.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SUBSCRIPTION_TRIAL_WILL_END = 'warble_media_phoenix.webhook.stripe.customer.subscription.trial_will_end';

    /**
     * The CUSTOMER_SUBSCRIPTION_UPDATED event occurs whenever a subscription changes. Examples would include
     * switching from one plan to another, or switching status from trial to active.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const CUSTOMER_SUBSCRIPTION_UPDATED = 'warble_media_phoenix.webhook.stripe.customer.subscription.updated';

    /**
     * The INVOICE_CREATED event occurs whenever a new invoice is created. If you are using webhooks, Stripe will
     * wait one hour after they have all succeeded to attempt to pay the invoice; the only exception here is on the
     * first invoice, which gets created and paid immediately when you subscribe a customer to a plan. If your
     * webhooks do not all respond successfully, Stripe will continue retrying the webhooks every hour and will not
     * attempt to pay the invoice. After 3 days, Stripe will attempt to pay the invoice regardless of whether or not
     * your webhooks have succeeded. See how to respond to a webhook.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICE_CREATED = 'warble_media_phoenix.webhook.stripe.invoice.created';

    /**
     * The INVOICE_PAYMENT_FAILED event occurs whenever an invoice attempts to be paid, and the payment fails. This can
     * occur either due to a declined payment, or because the customer has no active card. A particular case of note is
     * that if a customer with no active card reaches the end of its free trial, an invoice.payment_failed notification
     * will occur.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICE_PAYMENT_FAILED = 'warble_media_phoenix.webhook.stripe.invoice.payment_failed';

    /**
     * The INVOICE_PAYMENT_SUCCEEDED event occurs whenever an invoice attempts to be paid, and the payment succeeds.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICE_PAYMENT_SUCCEEDED = 'warble_media_phoenix.webhook.stripe.invoice.payment_succeeded';

    /**
     * The INVOICE_UPDATED event occurs whenever an invoice changes (for example, the amount could change).
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICE_UPDATED = 'warble_media_phoenix.webhook.stripe.invoice.updated';

    /**
     * The INVOICEITEM_CREATED event occurs whenever an invoice item is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICEITEM_CREATED = 'warble_media_phoenix.webhook.stripe.invoiceitem.created';

    /**
     * The INVOICEITEM_DELETED event occurs whenever an invoice item is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICEITEM_DELETED = 'warble_media_phoenix.webhook.stripe.invoiceitem.deleted';

    /**
     * The INVOICEITEM_UPDATED event occurs whenever an invoice item is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const INVOICEITEM_UPDATED = 'warble_media_phoenix.webhook.stripe.invoiceitem.updated';

    /**
     * The ORDER_CREATED event occurs whenever an order is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ORDER_CREATED = 'warble_media_phoenix.webhook.stripe.order.created';

    /**
     * The ORDER_PAYMENT_FAILED event occurs whenever payment is attempted on an order, and the payment fails.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ORDER_PAYMENT_FAILED = 'warble_media_phoenix.webhook.stripe.order.payment_failed';

    /**
     * The ORDER_PAYMENT_SUCCEEDED event occurs whenever payment is attempted on an order, and the payment succeeds.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ORDER_PAYMENT_SUCCEEDED = 'warble_media_phoenix.webhook.stripe.order.payment_succeeded';

    /**
     * The ORDER_UPDATED event occurs whenever an order is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ORDER_UPDATED = 'warble_media_phoenix.webhook.stripe.order.updated';

    /**
     * The ORDER_RETURN_CREATED event occurs whenever an order return created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const ORDER_RETURN_CREATED = 'warble_media_phoenix.webhook.stripe.order_return.created';

    /**
     * The PLAN_CREATED event occurs whenever a plan is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PLAN_CREATED = 'warble_media_phoenix.webhook.stripe.plan.created';

    /**
     * The PLAN_DELETED event occurs whenever a plan is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PLAN_DELETED = 'warble_media_phoenix.webhook.stripe.plan.deleted';

    /**
     * The PLAN_UPDATED event occurs whenever a plan is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PLAN_UPDATED = 'warble_media_phoenix.webhook.stripe.plan.updated';

    /**
     * The PRODUCT_CREATED event occurs whenever a product is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PRODUCT_CREATED = 'warble_media_phoenix.webhook.stripe.product.created';

    /**
     * The PRODUCT_DELETED event occurs whenever a product is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PRODUCT_DELETED = 'warble_media_phoenix.webhook.stripe.product.deleted';

    /**
     * The PRODUCT_UPDATED event occurs whenever a product is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PRODUCT_UPDATED = 'warble_media_phoenix.webhook.stripe.product.updated';

    /**
     * The RECIPIENT_CREATED event occurs whenever a recipient is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const RECIPIENT_CREATED = 'warble_media_phoenix.webhook.stripe.recipient.created';

    /**
     * The RECIPIENT_DELETED event occurs whenever a recipient is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const RECIPIENT_DELETED = 'warble_media_phoenix.webhook.stripe.recipient.deleted';

    /**
     * The RECIPIENT_UPDATED event occurs whenever a recipient is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const RECIPIENT_UPDATED = 'warble_media_phoenix.webhook.stripe.recipient.updated';

    /**
     * The SKU_CREATED event occurs whenever a SKU is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SKU_CREATED = 'warble_media_phoenix.webhook.stripe.sku.created';

    /**
     * The SKU_DELETED event occurs whenever a SKU is deleted.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SKU_DELETED = 'warble_media_phoenix.webhook.stripe.sku.deleted';

    /**
     * The SKU_UPDATED event occurs whenever a SKU is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SKU_UPDATED = 'warble_media_phoenix.webhook.stripe.sku.updated';

    /**
     * The SOURCE_CANCELED event occurs whenever a source is canceled.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SOURCE_CANCELED = 'warble_media_phoenix.webhook.stripe.source.canceled';

    /**
     * The SOURCE_CHARGEABLE event occurs whenever a source transitions to chargeable.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SOURCE_CHARGEABLE = 'warble_media_phoenix.webhook.stripe.source.chargeable';

    /**
     * The SOURCE_FAILED event occurs whenever a source is failed.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const SOURCE_FAILED = 'warble_media_phoenix.webhook.stripe.source.failed';

    /**
     * The TRANSFER_CREATED event occurs whenever a new transfer is created.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const TRANSFER_CREATED = 'warble_media_phoenix.webhook.stripe.transfer.created';

    /**
     * The TRANSFER_FAILED event occurs whenever Stripe attempts to send a transfer and that transfer fails.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const TRANSFER_FAILED = 'warble_media_phoenix.webhook.stripe.transfer.failed';

    /**
     * The TRANSFER_PAID event occurs whenever a sent transfer is expected to be available in the destination bank
     * account. If the transfer failed, a transfer.failed webhook will additionally be sent at a later time. Note to
     * Connect users: this event is only created for transfers from your connected Stripe accounts to their bank
     * accounts, not for transfers to the connected accounts themselves.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const TRANSFER_PAID = 'warble_media_phoenix.webhook.stripe.transfer.paid';

    /**
     * The TRANSFER_REVERSED event occurs whenever a transfer is reversed, including partial reversals.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const TRANSFER_REVERSED = 'warble_media_phoenix.webhook.stripe.transfer.reversed';

    /**
     * The TRANSFER_UPDATED event occurs whenever the description or metadata of a transfer is updated.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const TRANSFER_UPDATED = 'warble_media_phoenix.webhook.stripe.transfer.updated';

    /**
     * The PING event may be sent by Stripe at any time to see if a provided webhook URL is working.
     *
     * @Event("\WarbleMedia\PhoenixBundle\Event\StripeWebhookEvent")
     */
    const PING = 'warble_media_phoenix.webhook.stripe.ping';
}
