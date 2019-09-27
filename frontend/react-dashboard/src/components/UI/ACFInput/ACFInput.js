import React from 'react';
import './ACFInput.scss';

import SwitchToggle from '../SwitchToggle/SwitchToggle';

const ACFInput = (props) => {
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
            case ('number'):
                inputElement = <input
                    type={props.inputType}
                    className={inputClasses.join(' ')}
                    value={props.value}
                    onChange={props.changed}
                />;
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

    return (
        <div className="ACFInput">
            <label className="ACFInput__label">{props.label}</label>
            <p className="ACFInput__instructions">{props.instructions}</p>
            {inputElement}
        </div>
    );
};

export default ACFInput;