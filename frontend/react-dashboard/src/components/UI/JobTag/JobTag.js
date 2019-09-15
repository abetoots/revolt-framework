import React from 'react';
import './JobTag.css';
const JobTag = (props) => (
    <div className="JobTag">
        <h4>{props.tag}</h4>
    </div>
);

export default JobTag;