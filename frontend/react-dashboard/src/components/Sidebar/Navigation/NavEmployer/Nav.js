import React from 'react';
import './Nav.scss';

import NavigationItem from './NavigationItem/NavigationItem';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
const nav = (props) => {
    let navigationItems =
        <ul className="Nav__list">
            <div className="Nav__block">
                <NavigationItem link="/dashboard" exact >
                    <FontAwesomeIcon icon={['fas', 'th']} className="Nav__icon" />
                    Overview
                    </NavigationItem>
            </div>
            <div className="Nav__block">
                <NavigationItem link="/hire" exact>
                    <FontAwesomeIcon icon={['fas', 'user-friends']} className="Nav__icon" />
                    Hire Candidates
                        </NavigationItem>
            </div>
            <div className="Nav__block">
                <NavigationItem link="/post-job" exact>
                    <FontAwesomeIcon icon={['fas', 'clipboard']} className="Nav__icon" />
                    Post a Job
                    </NavigationItem>
            </div>
            <div className="Nav__block">
                <NavigationItem link="/jobs" exact>
                    <FontAwesomeIcon icon={['fas', 'briefcase']} className="Nav__icon" />
                    Your Jobs
                    </NavigationItem>
            </div>
            <div className="Nav__block">
                <NavigationItem link="/applications" exact>
                    <FontAwesomeIcon icon={['fas', 'id-card-alt']} className="Nav__icon" />
                    Applications
                        </NavigationItem>
            </div>
            <div className="Nav__block">
                <NavigationItem link="/profile-settings" exact>
                    <FontAwesomeIcon icon={['fas', 'cog']} className="Nav__icon" />
                    Profile Settings
                    </NavigationItem>
            </div>
        </ul>;

    if (props.jobseeker) {
        navigationItems =
            <ul className="Nav__list">
                <div className="Nav__block">
                    <NavigationItem link="/dashboard" exact >
                        <FontAwesomeIcon icon={['fas', 'th']} className="Nav__icon" />
                        Overview
                        </NavigationItem>
                </div>>
                    <div className="Nav__block">
                    <NavigationItem link="/jobs" exact>
                        <FontAwesomeIcon icon={['fas', 'briefcase']} className="Nav__icon" />
                        Jobs
                        </NavigationItem>
                </div>
                <div className="Nav__block">
                    <NavigationItem link="/profile-settings" exact>
                        <FontAwesomeIcon icon={['fas', 'cog']} className="Nav__icon" />
                        Profile Settings
                        </NavigationItem>
                </div>
            </ul>
    }
    return (
        <nav className="Nav">
            {navigationItems}
        </nav>

    );
}

export default nav;