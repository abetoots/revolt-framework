import React from 'react';
import './NavigationItem.scss';

import { NavLink } from 'react-router-dom';
const NavigationItem = (props) => (
    <li className="NavigationItem">
        <NavLink
            to={props.link}
            className="NavigationItem__link"
            exact={props.exact}>{props.children}
        </NavLink>
    </li>
);

export default NavigationItem;