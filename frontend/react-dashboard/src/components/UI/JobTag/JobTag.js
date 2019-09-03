import React from 'react';
import './JobTag.css';
const tag = (props) => (
    <div className="JobTag">
        <h3>{props.tag}</h3>
    </div>
);

export default tag;