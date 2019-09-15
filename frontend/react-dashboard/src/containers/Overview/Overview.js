import React, { Component } from 'react';
import './Overview.scss';

//Redux
import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';

//UI
import Job from '../Jobs/Job/Job';
import Candidate from '../Candidates/Candidate/Candidate';
import Spinner from '../../components/UI/Spinner/Spinner';
import Counter from '../../components/UI/Counter/Counter';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

//assets
import suitcase from '../../assets/suitcase.png';
import resume from '../../assets/resume.png';
import save from '../../assets/save.png';
export class Overview extends Component {
    componentDidMount() {
        //If jobs have not been loaded yet, then fetch
        if (!this.props.loadedRecentJobs) {
            this.props.fetchRecentJobsOnMount(this.props.userId);
        }

        if (!this.props.loadedJobs) {
            this.props.fetchJobsOnMount(this.props.userId);
        }

        // Make sure profile settings have been loaded before fetching saved candidates
        // Also, dont fetch when profile is new since settings will be empty
        if (this.props.loadedProfile && !this.props.userIsNew) {
            this.props.fetchSavedCandidatesOnMount(this.props.candidatesArray);
        }
    }

    render() {
        let recentJobs = <Spinner />;
        let savedCandidates = <Spinner />;
        if (this.props.loadedRecentJobs) {
            if (this.props.recentJobs.length > 0) {
                recentJobs = (
                    <div className="Overview__recentJobs">
                        <h2 className="Overview__heading">Recent Jobs: </h2>
                        {this.props.recentJobs.map(job =>
                            <Job
                                key={job.id}
                                id={job.id}
                                type={job.type}
                                title={job.title}
                                availability={job.jobFields.job_availability}
                                verified={job.verified}
                                authorPhoto={job.authorPhoto}
                                tags={job.tags}
                            />
                        )}
                    </div>
                );
            } else {
                recentJobs = (
                    <div className="Overview__recentJobs -missing">
                        <div className="Overview__recentJobsWrap">
                            <h2 className="Overview__heading"> No Recent Jobs <span role="img" aria-label="no-recent-jobs">🔎</span></h2>
                            <button className="Overview__btn">Post A Job <FontAwesomeIcon icon="location-arrow" /></button>
                        </div>
                    </div>
                );
            }
        }

        if (this.props.loadedSavedCands) {
            if (this.props.savedCands.length > 0) {
                savedCandidates =
                    <div className="Overview__savedCands">
                        <h2 className="Overview__heading">Saved Candidates</h2>
                        {this.props.savedCands.map(candidate =>
                            <Candidate key={candidate.id}
                                photoSrc={candidate.photoSrc}
                                fullName={candidate.name}
                                title={candidate.title}
                                overview={candidate.overview}
                                skills={candidate.skills}
                                available={candidate.availability}
                                salary={candidate.salary}
                            />
                        )}
                    </div>
            } else {
                savedCandidates =
                    <div className="Overview__savedCands -missing">
                        <div className="Overview__savedCandsWrap">
                            <h2 className="Overview__heading"> No Saved Candidates <span role="img" aria-label="no-recent-jobs">🔎</span></h2>
                            <button className="Overview__btn">Browse Candidates <FontAwesomeIcon icon="location-arrow" /></button>
                        </div>
                    </div>
            }
        }
        return (
            <div className="Overview">
                <div className="Overview__counterContainer">
                    <Counter imgSrc={suitcase} text="Job Posts"
                        count={this.props.loadedJobs ?
                            this.props.jobsCount :
                            <FontAwesomeIcon className="fa-pulse" icon="spinner" />}
                    />
                    <Counter imgSrc={resume} text="Applicants"
                        count={this.props.loadedJobs ?
                            actions.countApplicants(this.props.jobs) :
                            <FontAwesomeIcon className="fa-pulse" icon="spinner" />}
                    />
                    <Counter imgSrc={save} text="Saved Candidates" count={3} />
                </div>
                {recentJobs}
                {savedCandidates}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        //for fetching
        userId: state.auth.userId,
        userName: state.auth.userName,
        candidatesArray: state.profile.profileInfo.revolt_settings.revolt_emp_saved_candidates,
        userIsNew: state.profile.isNew,
        //loading
        loadedSavedCands: state.candidates.loadedSavedCandidates,
        loadedRecentJobs: state.jobs.loadedRecent,
        loadedJobs: state.jobs.loadedJobs,
        loadedProfile: state.profile.loaded,
        //errors
        jobsError: state.jobs.errorRecent,
        profileError: state.profile.error,
        candsError: state.candidates.error,
        //data
        recentJobs: state.jobs.recentJobs,
        savedCands: state.candidates.savedCands,
        jobsCount: state.jobs.jobs.length,
        jobs: state.jobs.jobs
    }
}
const mapDispatchToProps = dispatch => {
    return {
        fetchRecentJobsOnMount: (userId) => dispatch(actions.fetchRecentJobs(userId)),
        fetchSavedCandidatesOnMount: (candidatesArray) => dispatch(actions.fetchSavedCandidates(candidatesArray)),
        fetchJobsOnMount: (userId) => dispatch(actions.fetchJobs(userId))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Overview);