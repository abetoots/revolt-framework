import * as actionTypes from './actionTypes';
import axios from '../../axios-wp-dev-only';

export const fetchJobsStart = () => {
    return {
        type: actionTypes.FETCH_JOBS_START
    }
}

export const fetchJobsSuccess = jobData => {
    return {
        type: actionTypes.FETCH_JOBS_SUCCESS,
        data: jobData
    }
}

export const fetchJobsFailed = error => {
    return {
        type: actionTypes.FETCH_JOBS_FAILED,
        error: error
    }
}
//TODO check data has been refactored from backend 
export const fetchJobs = (userId) => {
    return dispatch => {
        dispatch(fetchJobsStart());
        axios.get("/wp-json/wp/v2/revolt-job-post?author=" + userId)
            .then(response => {
                const fetchedJobs = [];
                for (let job of response.data) {
                    fetchedJobs.push({
                        id: job.id,
                        title: job.title.rendered,
                        type: job.employment_types_data,
                        name: job['job-post-meta-fields']['revolt-job-post-company-field'][0],
                        accepts: 'WorldWide',
                        verified: true,
                        employerPhoto: job.revolt_employer_photo,
                        tags: [job.job_categories_data]
                    });
                }
                dispatch(fetchJobsSuccess(fetchedJobs));
            })
            .catch(error => {
                console.log(error);
                dispatch(fetchJobsFailed(error));
            })
    }
}