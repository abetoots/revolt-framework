import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    jobs: [],
    recentJobs: [],
    loadedJobs: false,
    loadedRecent: false,
    errorJobs: null,
    errorRecent: null,
    errorEdit: null,
    loadingJobs: false,
    loadingRecent: false,
    editing: false,
    editSuccess: false,
}

const editJobSuccess = (state, action) => {
    //clone our existing jobs
    const clone = [...state.jobs];
    //replace the appropriate index with the new data
    clone[action.jobIndex] = action.newData;
    console.log(clone);
    return updateObject(state, {
        jobs: clone,
        editing: false,
        errorEdit: null,
        editSuccess: true
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_JOBS_START:
            return updateObject(state, { error: false, loadingJobs: true })
        case actionTypes.FETCH_JOBS_SUCCESS:
            return updateObject(state, { jobs: action.jobData, errorJobs: false, loadingJobs: false, loadedJobs: true })
        case actionTypes.FETCH_JOBS_FAILED:
            return updateObject(state, { errorJobs: action.error, loadingJobs: false })
        case actionTypes.FETCH_RECENT_JOBS_START:
            return updateObject(state, { errorRecent: false, loadingRecent: true })
        case actionTypes.FETCH_RECENT_JOBS_SUCCESS:
            return updateObject(state, { recentJobs: action.jobData, error: false, loadingRecent: false, loadedRecent: true })
        case actionTypes.FETCH_RECENT_JOBS_FAILED:
            return updateObject(state, { errorRecent: action.error, loadingRecent: false })
        case actionTypes.EDIT_JOB_START:
            return updateObject(state, { editing: true, errorEdit: null, editSuccess: false });
        case actionTypes.EDIT_JOB_SUCCESS:
            return editJobSuccess(state, action);
        case actionTypes.EDIT_JOB_FAILED:
            return updateObject(state, { editing: false, errorEdit: action.error, editSuccess: false });
        default:
            return state;
    }
}

export default reducer;