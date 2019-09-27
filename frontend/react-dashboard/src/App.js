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
import Candidates from './containers/Candidates/Candidates';
import PageDisplay from './containers/PageDisplay/PageDisplay';
import Jobs from './containers/Jobs/Jobs';
import EditJob from './containers/EditJob/EditJob';
import PostJob from './containers/PostJob/PostJob';
import Logout from './containers/Logout/Logout';

export class App extends Component {

    componentDidMount() {
        this.props.checkTokenOnMount();
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevProps.isAuthenticated === false && this.props.isAuthenticated === true) {
            this.props.fetchProfileOnMount(this.props.userName);
        }
    }

    render() {
        let renderedComp =
            <Aux>
                <Switch>
                    <Route path="/dashboard/authenticate" exact component={Auth} />
                    <Redirect to="/dashboard/authenticate" />
                </Switch>
            </Aux>

        if (this.props.isAuthenticated && this.props.loadedProfile && !this.props.userIsNew) {
            renderedComp =
                <Aux>
                    <Sidebar userName={this.props.userName} />
                    <PageDisplay>
                        <Switch>
                            <Route path="/dashboard/authenticate" exact component={Auth} />
                            <Route path="/dashboard/overview" exact component={Overview} />
                            <Route path="/dashboard/hire" exact component={Candidates} />
                            <Route path="/dashboard/jobs" exact component={Jobs} />
                            <Route path="/dashboard/jobs/edit/:jobIndex" exact component={EditJob} />
                            <Route path="/dashboard/jobs/new" exact component={PostJob} />
                            <Route path="/dashboard/logout" exact component={Logout} />
                            <Redirect to="/dashboard/overview" />
                        </Switch>
                    </PageDisplay>
                </Aux>;
        };
        return (
            <div className={this.props.loadedProfile ? "RevoltReactDashboard" : ''}>
                {renderedComp}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        isAuthenticated: state.auth.valid,
        userName: state.auth.userName,
        loadedProfile: state.profile.loaded,
        userIsNew: state.profile.isNew
    }
}

const mapDispatchToProps = dispatch => {
    return {
        checkTokenOnMount: () => dispatch(actions.checkToken()),
        fetchProfileOnMount: (userName) => dispatch(actions.fetchProfile(userName))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
