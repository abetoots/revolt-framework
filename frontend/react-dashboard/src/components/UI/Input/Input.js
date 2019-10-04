import React from 'react';
import './Input.scss';

const Input = (props) => {
    let inputElement = null;
    //add invalid css class 'invalid' when invalid for better ux
    const inputClasses = ['Input__element'];
    if (!props.valid && props.touched && props.shouldValidate) {
        inputClasses.push('-invalid');
    }

    //switch case for normal inputs
    switch (props.elementType) {
        case ('input'):
            inputElement = <input
                className={inputClasses.join(' ')}
                {...props.elementConfig}
                value={props.value}
                onChange={props.changed} />;
            break;
        case ('textarea'):
            inputElement = <textarea
                className={inputClasses.join(' ')}
                {...props.elementConfig}
                value={props.value}
                onChange={props.changed} />;
            break;
        case ('select'):
            inputElement =
                <select
                    className={inputClasses.join(' ')}
                    value={props.value}
                    onChange={props.changed}>
                    {props.elementConfig.options.map(option => (
                        <option
                            key={option.value}
                            value={option.value}>
                            {option.displayValue}
                        </option>
                    ))}
                </select>
            break;

        default:
            inputElement = <input
                className={inputClasses.join(' ')}
                {...props.elementConfig}
                value={props.value}
                onChange={props.changed} />;
    }

    return (
        <div className="Input">
            <label className="Input__label">{props.label}</label>
            {inputElement}
        </div>
    );
};

export default Input;