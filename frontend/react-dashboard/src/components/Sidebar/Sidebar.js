import React, { useState } from 'react';
import './Sidebar.scss';

import Aux from '../../hoc/Auxiliary';
import MenuBurger from '../UI/MenuBurger/MenuBurger';
import Backdrop from '../UI/Backdrop/Backdrop';
import ProfileBanner from './ProfileBanner/ProfileBanner';
import Nav from './Navigation/Navigation';

const Sidebar = (props) => {
    const [showSidebar, setShowSidebar] = useState(false);

    let sidebarClassNames = 'Sidebar'
    if (showSidebar) {
        sidebarClassNames = 'Sidebar -open'
    }

    const toggleHandler = () => {
        setShowSidebar(prevState => !prevState)
    }

    const closedHandler = () => {
        setShowSidebar(false);
    }

    return (
        <Aux>
            <Backdrop show={showSidebar} clicked={closedHandler} />
            <MenuBurger sidebarShown={showSidebar} toggle={toggleHandler} />
            <section className={sidebarClassNames}>
                <ProfileBanner userName={props.userName} />
                <Nav />
            </section>
        </Aux >

    );
}

export default Sidebar;