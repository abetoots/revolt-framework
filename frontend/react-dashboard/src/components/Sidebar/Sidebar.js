import React from 'react';
import './Sidebar.css';

import Aux from '../../hoc/Auxiliary';
import ProfileBanner from './ProfileBanner/ProfileBanner';
import Nav from './Navigation/NavEmployer/Nav';
import Logout from './Logout/Logout';
const sidebar = (props) => {
    let sidebar =
        <Aux>
            <ProfileBanner userName={props.userName} />
            <Nav />
            <Logout to="/logout" exact />
        </Aux>

    if (props.role === 'jobseeker') {
        sidebar =
            <Aux>
                <ProfileBanner userName={props.userName} />
                <Nav jobseeker />
                <Logout to="/logout" exact />
            </Aux>
    }
    return (
        <section className="Sidebar">
            {sidebar}
        </section>

    );
}

export default sidebar;