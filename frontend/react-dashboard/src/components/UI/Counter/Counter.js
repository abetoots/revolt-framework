import React from 'react';
import './Counter.scss';

const Counter = (props) => (
    <div className="Counter">
        <img className="Counter__item -img" src={props.imgSrc} alt="counter" />
        <span className="Counter__item -text">{props.text}</span>
        <span className="Counter__item -count">{props.count}</span>
    </div>
);

export default Counter;