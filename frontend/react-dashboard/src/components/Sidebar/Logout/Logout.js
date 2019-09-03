import React from 'react';
import { NavLink } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import './Logout.scss';

const logout = (props) => (
    <div className="Logout">
        <NavLink to={props.to} exact={props.exact} className="Logout__link" >
            <FontAwesomeIcon className="Logout__icon" icon={['fas', 'sign-out-alt']} />
            Logout
            </NavLink>
    </div>
);

export default logout;