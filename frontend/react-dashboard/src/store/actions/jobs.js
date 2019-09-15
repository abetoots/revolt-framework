import * as actionTypes from './actionTypes';
import axios from '../../axios-instance';

export const fetchJobsStart = () => {
    return {
        type: actionTypes.FETCH_JOBS_START
    }
}

export const fetchJobsSuccess = jobData => {
    return {
        type: actionTypes.FETCH_JOBS_SUCCESS,
        jobData: jobData
    }
}

export const fetchJobsFailed = error => {
    return {
        type: actionTypes.FETCH_JOBS_FAILED,
        error: error
    }
}

export const fetchJobs = (userId) => {
    return dispatch => {
        dispatch(fetchJobsStart());
        axios.get("/wp-json/wp/v2/revolt-job-post?author=" + userId)
            .then(response => {
                const fetchedJobs = response.data.map(job => {
                    return {
                        id: job.id,
                        title: job.title.rendered,
                        content: job.content.rendered,
                        applicants: job.job_applicants,
                        type: job['the_employment_types_data'],
                        tags: job['the_job_categories_data'],
                        author: job.job_author,
                        authorPhoto: job.job_author_photo,
                        jobFields: job['job_acf_fields']
                    }
                });
                dispatch(fetchJobsSuccess(fetchedJobs));
            })
            .catch(error => {
                dispatch(fetchJobsFailed(error));
            })
    }
}

export const countApplicants = jobs => {
    let count = 0;
    jobs.forEach(job => {
        job.applicants.forEach(() => count++);
    })
    return count;
}

export const fetchRecentJobsStart = () => {
    return {
        type: actionTypes.FETCH_RECENT_JOBS_START
    }
}

export const fetchRecentJobsSuccess = jobData => {
    return {
        type: actionTypes.FETCH_RECENT_JOBS_SUCCESS,
        jobData: jobData
    }
}

export const fetchRecentJobsFailed = error => {
    return {
        type: actionTypes.FETCH_RECENT_JOBS_FAILED,
        error: error
    }
}
export const fetchRecentJobs = userId => {
    return dispatch => {
        dispatch(fetchRecentJobsStart());
        //WP after/before arguments only accept dates in ISO 8601 format
        //Get jobs posted 12 hours ago
        const dateToCheck = new Date(Date.now() - (12 * 60 * 60 * 1000)).toISOString();
        axios.get(`/wp-json/wp/v2/revolt-job-post?author=${userId}&after=${dateToCheck}`)
            .then(response => {
                const fetchedJobs = response.data.map(job => {
                    return {
                        id: job.id,
                        title: job.title.rendered,
                        content: job.content.rendered,
                        applicants: job.job_applicants,
                        type: job['the_employment_types_data'],
                        tags: job['the_job_categories_data'],
                        author: job.job_author,
                        authorPhoto: job.job_author_photo,
                        jobFields: job['job_acf_fields']
                    }
                });
                dispatch(fetchRecentJobsSuccess(fetchedJobs));
            })
            .catch(error => {
                console.log(error);
                dispatch(fetchRecentJobsFailed(error));
            })
    }
}

export const editJobStart = () => {
    return {
        type: actionTypes.EDIT_JOB_START
    }
}

export const editJobSuccess = (jobData, jobIndex) => {
    return {
        type: actionTypes.EDIT_JOB_SUCCESS,
        newData: jobData,
        jobIndex: jobIndex
    }
}

export const editJobFailed = error => {
    return {
        type: actionTypes.EDIT_JOB_FAILED,
        error: error
    }
}

export const editJob = (formData, jobId, token, jobIndex) => {
    return dispatch => {
        dispatch(editJobStart());
        axios.post(`/wp-json/wp/v2/revolt-job-post/${jobId}`, formData, { headers: { "Authorization": "Bearer " + token } })
            .then(response => {
                console.log(response.data);
                const picked = {
                    id: response.data.id,
                    title: response.data.title.rendered,
                    content: response.data.content.rendered,
                    applicants: response.data.job_applicants,
                    type: response.data['the_employment_types_data'],
                    tags: response.data['the_job_categories_data'],
                    author: response.data.job_author,
                    authorPhoto: response.data.job_author_photo,
                    jobFields: response.data['job_acf_fields']
                };
                console.log(picked);
                dispatch(editJobSuccess(picked, jobIndex));
            })
            .catch(error => {
                console.log(error);
                dispatch(editJobFailed(error));
            })
    }
}