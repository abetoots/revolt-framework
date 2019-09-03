import React from 'react';
import './ProfileBanner.scss';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

const profileBanner = (props) => (
    <div className="ProfileBanner">
        <p>Welcome back!</p>
        <div className="ProfileBanner__block">
            <h2>{props.userName}</h2>
            {props.src ?
                <img className="ProfileBanner__photo" alt="Employer" src={props.src} /> :
                <FontAwesomeIcon className="ProfileBanner__icon" icon={['fas', 'user']} size="2x" />
            }
        </div>

    </div>
);

export default profileBanner;