import React from 'react';
import './SwitchToggle.scss';

const SwitchToggle = (props) => {
    return (
        <label className="SwitchToggle">
            <input type="checkbox"
                className={`SwitchToggle__input ${props.classes}`}
                value={props.value}
                onChange={props.onChange}
                checked={props.value}
            />
            <span className="SwitchToggle__slider"></span>
        </label>
    )
};

export default SwitchToggle;