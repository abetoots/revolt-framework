import * as actionTypes from './actionTypes';
import axios from '../../axios-wp-dev-only';

export const fetchJobStart = () => {
    return {
        type: actionTypes.FETCH_JOB_START
    }
}


export const fetchJobSuccess = jobData => {
    return {
        type: actionTypes.FETCH_JOB_SUCCESS,
        title: jobData.title.rendered,
        description: jobData.content.rendered,
        category: jobData.job_categories_data,
        jobType: jobData.employment_types_data,
        deadline: jobData.deadline,
        qualifications: jobData.job_qualifications_data
    }
}

export const fetchJobFailed = error => {
    return {
        type: actionTypes.FETCH_JOB_FAILED,
        error: error
    }
}


export const fetchJob = jobID => {
    return dispatch => {
        dispatch(fetchJobStart());
        axios.get("/wp-json/wp/v2/revolt-job-post/" + jobID)
            .then(response => {
                console.log(response.data);
                dispatch(fetchJobSuccess(response.data));
            })
            .catch(error => {
                console.log(error);
                dispatch(fetchJobFailed(error));
            })
    }
}