import React, { Component } from 'react';
import './PageDisplay.scss';
class PageDisplay extends Component {
    render() {
        return (
            <section className="PageDisplay">
                {this.props.children}
            </section>
        );
    }
};

export default PageDisplay;