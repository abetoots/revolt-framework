import React, { Component } from "react";
import './App.scss';

import { Route, Switch, Redirect } from "react-router-dom";

//hoc
import Aux from './hoc/Auxiliary';

//Redux
import { connect } from 'react-redux';
import * as actions from './store/actions/index';

//UI
import Sidebar from "./components/Sidebar/Sidebar";
//Containers
import Auth from './containers/Auth/Auth';
import Overview from './containers/Overview/Overview';
import Candidates from "./containers/Employer/Candidates/Candidates";
import PageDisplay from "./containers/PageDisplay/PageDisplay";
import Profile from "./containers/Profile/Profile";
import PostJob from "./containers/Employer/PostJob/PostJob";
import Jobs from "./containers/Jobs/Jobs";
import EditJob from "./containers/Employer/EditJob/EditJob";
import Logout from './containers/Logout/Logout';

export class App extends Component {
    componentDidMount() {
        this.props.checkAuthenticationOnMount();
    }

    render() {
        let renderedComp =
            <Aux>
                <Switch>
                    <Route path="/login" exact component={Auth} />
                    <Redirect to="/login" />
                </Switch>;
            </Aux>

        if (this.props.isAuthenticated) {
            if (this.props.userRole === 'administrator' || 'employer') {
                renderedComp =
                    <Aux>
                        <Sidebar role={this.props.userRole} userName={this.props.userName} />
                        <PageDisplay>
                            <Switch>
                                <Route path="/login" exact component={Auth} />
                                <Route path="/overview" exact component={Overview} />
                                <Route path="/hire" exact component={Candidates} />
                                <Route path="/profile-settings" exact component={Profile} />
                                <Route path="/post-job" exact component={PostJob} />
                                <Route path="/jobs" exact component={Jobs} />
                                <Route path="/jobs/edit/:jobID" exact component={EditJob} />
                                <Route path="/logout" exact component={Logout} />
                                <Redirect to="/overview" />
                                {/* 
                            <Route path="/applications" exact component={Applications} />

                              */}
                            </Switch>
                        </PageDisplay>
                    </Aux>;
            }
        };
        return (
            <div className={this.props.isAuthenticated ? "RevoltReactDashboard" : ''}>
                {renderedComp}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        isAuthenticated: state.auth.token !== null,
        userName: state.auth.userName,
        userRole: state.auth.userRole
    }
}

const mapDispatchToProps = dispatch => {
    return {
        checkAuthenticationOnMount: () => dispatch(actions.checkAuthentication())
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
