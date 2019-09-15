import React from 'react';
import './Navigation.scss';

import NavigationItem from './NavigationItem/NavigationItem';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
const Navigation = (props) => {
    let navigationItems =
        <ul className="Navigation__list">
            <div className="Nav__block">
                <NavigationItem link="/dashboard/overview" exact >
                    <FontAwesomeIcon icon={['fas', 'th']} className="Navigation__icon" />
                    <span>Overview</span>
                </NavigationItem>
            </div>
            <div className="Navigation__block">
                <NavigationItem link="/dashboard/hire" exact>
                    <FontAwesomeIcon icon={['fas', 'user-friends']} className="Navigation__icon" />
                    <span>Browse Candidates</span>
                </NavigationItem>
            </div>
            <div className="Navigation__block">
                <NavigationItem link="/dashboard/jobs" exact>
                    <FontAwesomeIcon icon={['fas', 'briefcase']} className="Navigation__icon" />
                    <span>Your Jobs</span>
                </NavigationItem>
            </div>
        </ul>;
    return (
        <nav className="Navigation">
            {navigationItems}
        </nav>

    );
}

export default Navigation;