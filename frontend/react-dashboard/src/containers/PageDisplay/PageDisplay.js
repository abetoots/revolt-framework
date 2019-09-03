import React, { Component } from 'react';
import './PageDisplay.css';
class PageDisplay extends Component {
    render() {
        return (
            <section className="PageDisplay">
                <p>Error Area</p>
                {this.props.children}
            </section>
        );
    }
};

export default PageDisplay;