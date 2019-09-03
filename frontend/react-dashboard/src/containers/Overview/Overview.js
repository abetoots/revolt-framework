import React, { Component } from 'react';
import './Overview.scss';

import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';

import List from '../../components/List/List';

class Overview extends Component {
    componentDidMount() {
        console.log('mounted');
        //TODO maybe improve empty array checks
        //If jobs have not been loaded yet, then fetch
        if (this.props.loadedJobs) {
            this.props.fetchJobsOnMount(this.props.userId);
        }

        // Make sure profile settings have been loaded before fetching saved candidates
        // Also, dont fetch when profile is new since settings will be empty
        if (this.props.loadedProfile && !this.props.profIsNew && this.props.arrOfIds) {
            this.props.fetchSavedCandidates(this.props.arrOfIds);
        }
    }

    render() {
        return (
            <div className="Overview">
                <List
                    listGroup="Job Posts"
                    listData={this.props.jobs}
                    error={this.props.jobsError}
                    loading={this.props.loadingJobs}
                    tabs={['Your Job Posts']} />
                <List
                    listGroup="Saved Candidates"
                    listData={this.props.savedCands}
                    error={this.props.candsError}
                    loading={this.props.loadingSavedCands}
                    tabs={['All', 'Available', 'Unavailable']} />
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        //for fetching
        loadedJobs: state.jobs.loaded,
        loadedProfile: state.profile.loaded,
        userId: state.auth.userId,
        userName: state.auth.userName,
        arrOfIds: state.profile.savedCandIds,
        profIsNew: state.profile.isNew,
        //loading
        loadingJobs: state.jobs.loading,
        loadingSavedCands: state.candidates.loading,
        //errors
        jobsError: state.jobs.error,
        profileError: state.profile.error,
        candsError: state.candidates.error,
        jobs: state.jobs.jobs,
        savedCands: state.candidates.savedCands,
    }
}
const mapDispatchToProps = dispatch => {
    return {
        fetchJobsOnMount: (userId) => dispatch(actions.fetchJobs(userId)),
        fetchProfileOnMount: (userName) => dispatch(actions.fetchProfile(userName)),
        fetchSavedCandidates: (arrOfIds) => dispatch(actions.fetchSavedCandidates(arrOfIds))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Overview);