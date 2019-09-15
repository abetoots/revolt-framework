import React from 'react';
import './Available.css';

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

const Available = (props) => {
    let render = '';
    render = props.available ?
        <FontAwesomeIcon className="Available -true" title="Availability" icon={['fas', 'check']} size={props.size} /> :
        <FontAwesomeIcon className="Available -false" title="Availability" icon={['fas', 'exclamation-circle']} size={props.size} />;
    return (
        <div className="Available">
            <h6>Availability: </h6>
            {render}
        </div>
    );
};

export default Available;