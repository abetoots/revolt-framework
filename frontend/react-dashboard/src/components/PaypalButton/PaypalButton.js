import React, { useRef, useEffect } from 'react';
import { useScript } from '../../utility/hooks';

const PaypalButton = (props) => {
    const [loading, done] = useScript('https://www.paypal.com/sdk/js?client-id=AYgo98qsgMTD9zGpFJEzMfChu3LPeNApGqm9hRDODww8baCFggomQTE3f8kY4HS94WY0O_s-seygQdrU');
    const paypalRef = useRef();

    useEffect(() => {
        const hasWindow = window !== undefined && window.paypal !== undefined;
        if (hasWindow) {
            if (!loading && done && !props.loading) {
                console.log(window);
                window.paypal
                    .Buttons({
                        // Set up the transaction
                        createOrder: (data, actions) => {
                            return actions.order.create({
                                purchase_units: [{
                                    description: props.title,
                                    amount: {
                                        value: props.productPrice,
                                        currency_code: props.currency
                                    }
                                }]
                            });
                        },

                        // Finalize the transaction
                        onApprove: async (data, actions) => {
                            const order = await actions.order.capture();
                            props.approved(order);
                        },

                        onError: (err) => {
                            console.log(err)
                        }
                    })
                    .render(paypalRef.current);
            }
        }
    }, [loading, done, props]);

    return (
        <div ref={paypalRef}></div>
    )
};

export default PaypalButton;