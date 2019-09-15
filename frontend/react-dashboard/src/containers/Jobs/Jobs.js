import React, { Component } from 'react'
import './Jobs.scss';
//hoc
import { connect } from 'react-redux';
import Aux from '../../hoc/Auxiliary';
//redux
import * as actions from '../../store/actions/index';
//UI
import Job from './Job/Job';
import Spinner from '../../components/UI/Spinner/Spinner';
import Button from '../../components/UI/Button/Button';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
class Jobs extends Component {

    componentDidMount() {
        if (!this.props.loadedJobs && !this.props.loading) {
            this.props.fetchJobsOnMount(this.props.userId);
        }
    }

    editJobClickedHandler(index) {
        this.props.history.push({
            pathname: this.props.match.url + `/edit/${index}`
        });
    }
    render() {
        //switch between error, spinner, or the jobs
        let renderedChild = this.props.error ? <p>It appears that the jobs cannot be loaded!</p> : <Spinner />;;
        if (this.props.loadedJobs) {
            if (this.props.jobs.length > 0) {
                renderedChild = (
                    <div className="Jobs__wrap">
                        {this.props.jobs.map((job, index) => {
                            return (
                                <Aux key={job.id}>
                                    <div className="Jobs__edit">
                                        <Job
                                            id={job.id}
                                            type={job.type}
                                            title={job.title}
                                            author={job.author}
                                            availability={job.jobFields.job_availability}
                                            verified={job.verified}
                                            authorPhoto={job.authorPhoto}
                                            tags={job.tags}
                                        />
                                        <div className="Job__employerButtons">
                                            <Button clicked={() => this.editJobClickedHandler(index)} btnType="action -normal">
                                                <FontAwesomeIcon icon={['fas', 'edit']} />
                                            </Button>
                                            <Button clicked={this.deleteHandler} btnType="action -delete">
                                                <FontAwesomeIcon icon={['fas', 'trash-alt']} />
                                            </Button>
                                        </div>
                                    </div>
                                </Aux>
                            );
                        })}
                    </div>
                );
            } else {
                renderedChild =
                    <div className="Jobs__wrap -missing">
                        <h2 className="Jobs__heading"> You don't have any job posts yet. <span role="img" aria-label="no-recent-jobs">🔎</span></h2>
                        <button className="Jobs__postBtn">Post A Job <FontAwesomeIcon icon="location-arrow" /></button>
                    </div>
            }

        }

        return (
            <div className="Jobs">
                {renderedChild}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        //fetching
        userId: state.auth.userId,
        userName: state.auth.userName,
        loading: state.jobs.loadingJobs,
        loadedJobs: state.jobs.loadedJobs,
        //response data
        jobs: state.jobs.jobs,
        error: state.jobs.errorJobs,

    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchJobsOnMount: (userId) => dispatch(actions.fetchJobs(userId)),
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Jobs);