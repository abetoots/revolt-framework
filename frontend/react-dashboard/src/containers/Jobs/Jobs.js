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
        console.log('jobs did mount');
        if (this.props.jobs && !this.props.loading) {
            this.props.initFetchJobs(this.props.userId);
        }
    }

    editJobClickedHandler(id) {
        this.props.history.push({
            pathname: this.props.match.url + '/edit/' + id
        });
    }
    render() {
        //switch between error, spinner, or the jobs
        let renderedChild = '';
        renderedChild = this.props.error ? <p>It appears that the jobs cannot be loaded!</p> : <Spinner />;
        if (this.props.jobs) {
            renderedChild = (
                this.props.jobs.map(job => {
                    return (
                        <Aux key={job.id}>
                            <div className="Jobs__edit">
                                <Job
                                    id={job.id}
                                    type={job.type}
                                    title={job.title}
                                    name={job.name}
                                    location={job.accepts}
                                    verified={job.verified}
                                    employerPhoto={job.employerPhoto}
                                    tags={job.tags}
                                />
                                <div className="Job__employerButtons">
                                    <Button clicked={() => this.editJobClickedHandler(job.id)} btnType="action -normal">
                                        <FontAwesomeIcon icon={['fas', 'edit']} />
                                    </Button>
                                    <Button clicked={this.deleteHandler} btnType="action -delete">
                                        <FontAwesomeIcon icon={['fas', 'trash-alt']} />
                                    </Button>
                                </div>
                            </div>
                        </Aux>
                    );
                })
            );
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
        jobs: state.jobs.jobs,
        loading: state.jobs.loading,
        error: state.jobs.error,
        userId: state.auth.userId,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        initFetchJobs: (userId) => dispatch(actions.fetchJobs(userId))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Jobs);