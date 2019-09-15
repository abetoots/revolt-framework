import React from 'react';
import './MenuBurger.scss';

const MenuBurger = (props) => {
    let className = props.sidebarShown ? 'MenuBurger -toggled' : 'MenuBurger';
    return (
        <button className={className} aria-controls="burger-menu" aria-expanded={props.sidebarShown} onClick={props.toggle}>
            <div className="MenuBurger__bar -one"></div>
            <div className="MenuBurger__bar -two"></div>
            <div className="MenuBurger__bar -three"></div>
        </button>
    );
}


export default MenuBurger;