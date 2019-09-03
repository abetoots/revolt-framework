import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    fetchedJob: {
        title: '',
        description: '',
        category: '',
        jobType: '',
        deadline: '',
        qualifications: ''
    },
    error: null,
    loading: false
}

const fetchJobSuccess = (state, action) => {
    const updatedJob = updateObject(state.fetchedJob, {
        title: action.title,
        description: action.description,
        category: action.category,
        jobType: action.jobType,
        deadline: action.deadline,
        qualifications: action.qualifications
    })
    return updateObject(state, {
        fetchedJob: updatedJob,
        loading: false
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_JOB_START:
            return updateObject(state, { error: null, loading: true });
        case actionTypes.FETCH_JOB_SUCCESS:
            return fetchJobSuccess(state, action);
        case actionTypes.FETCH_JOB_FAILED:
            return updateObject(state, { error: action.error, loading: false });
        default:
            return state;
    }
}

export default reducer;