import React from 'react';
import './Input.scss';

import SwitchToggle from '../SwitchToggle/SwitchToggle';

const Input = (props) => {
    let inputElement = null;
    //add invalid css class 'invalid' when invalid for better ux
    const inputClasses = ['Input__element'];
    if (!props.valid && props.touched && props.shouldValidate) {
        inputClasses.push('-invalid');
    }

    //switch case for acf inputs
    if (props.inputType) {
        switch (props.inputType) {
            case ('true_false'):
                inputElement =
                    <SwitchToggle
                        classes={inputClasses.join(' ')}
                        value={props.value}
                        onChange={props.changed}
                        checked={props.value}
                    />
                break;

            case ('taxonomy'):
                // debugger;
                switch (props.fieldType) {
                    case ('radio'):
                        inputElement =
                            <ul className="Input__ul">
                                {props.taxonomyOptions.map(term => (
                                    <li key={term.slug}>
                                        <input
                                            id={term.slug}
                                            name={term.taxonomy}
                                            type="radio"
                                            value={term.id}
                                            checked={props.value === term.id}
                                            onChange={props.changed}
                                        />
                                        <label htmlFor={term.slug}>{term.name}</label>
                                    </li>
                                ))}
                            </ul>

                        break;
                    case ('checkbox'):
                        inputElement =
                            <ul className="Input__ul">
                                {props.taxonomyOptions.map(term => (
                                    <li key={term.slug}>
                                        <input
                                            id={term.slug}
                                            name={term.taxonomy}
                                            type="checkbox"
                                            value={term.id}
                                            checked={props.value.includes(term.id)}
                                            onChange={props.changed}
                                        />
                                        <label htmlFor={term.slug}>{term.name}</label>
                                    </li>
                                ))}
                            </ul>
                        break
                    case ('select'):
                        inputElement =
                            <select
                                value={props.value}
                                onChange={props.changed}>
                                {props.options.map(option => (
                                    <option
                                        key={option.value}
                                        value={option.value}>
                                        {option.displayValue}
                                    </option>
                                ))}
                            </select>
                        break;
                    default:
                        return;
                }

                break;

            case ('radio'):
                inputElement =
                    <ul className="Input__ul">
                        {props.defaultChoices.map(([value, label]) => (
                            <li key={value}>
                                <input
                                    id={value}
                                    name={props.name}
                                    type="radio"
                                    value={label}
                                    checked={props.value === label}
                                    onChange={props.changed}
                                />
                                <label htmlFor={value}>{label}</label>
                            </li>
                        ))}
                    </ul>
                break;
            default:
                inputElement = <input
                    type={props.inputType}
                    className={inputClasses.join(' ')}
                    value={props.value}
                    onChange={props.changed}
                    placeholder="Default placeholder" />;
        }
    } //end switch case for acf inputs

    //switch case for normal inputs
    if (props.elementType) {
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
    } // end switch case for normal inputs

    return (
        <div className="Input">
            <label className="Input__label">{props.label}</label>
            {inputElement}
        </div>
    );
};

export default Input;