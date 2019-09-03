import React, { Component } from 'react';
import { Redirect } from 'react-router';

import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';
class Logout extends Component {

    componentDidMount() {
        this.props.onLogout(this.props.token);
    }

    render() {
        return (
            <Redirect to="/" />
        );
    }
};

const mapStateToProps = state => {
    return {
        token: state.auth.token
    }
}


const mapDispatchToProps = dispatch => {
    return {
        onLogout: (token) => dispatch(actions.logout(token))
    }
}
export default connect(mapStateToProps, mapDispatchToProps)(Logout);