import React from 'react';
import './Stat.css';

const stat = (props) => (
    <div className="Stat">
        <h6>{props.statName}</h6>
        <p>{props.statCount}</p>
    </div>
);

export default stat;