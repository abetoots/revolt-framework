import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    candidates: [],
    savedCands: [],
    loading: false,
    error: null
}

const fetchCandidatesSuccess = (state, action) => {
    return updateObject(state, {
        candidates: action.candidates,
        loading: false
    })
}

const fetchCandidatesFailed = (state, action) => {
    return updateObject(state, {
        loading: false,
        error: action.error
    })
}

const fetchSavedCandidatesSuccess = (state, action) => {
    return updateObject(state, {
        savedCands: action.savedCandidates,
        loading: false
    })
}

const fetchSavedCandidatesFailed = (state, action) => {
    return updateObject(state, {
        loading: false,
        error: action.error
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_CANDIDATES_START: return updateObject(state, { loading: true });
        case actionTypes.FETCH_CANDIDATES_SUCCESS: return fetchCandidatesSuccess(state, action);
        case actionTypes.FETCH_CANDIDATES_FAILED: return fetchCandidatesFailed(state, action);
        case actionTypes.FETCH_SAVED_CANDIDATES_START: return updateObject(state, { loading: true });
        case actionTypes.FETCH_SAVED_CANDIDATES_SUCCESS: return fetchSavedCandidatesSuccess(state, action);
        case actionTypes.FETCH_SAVED_CANDIDATES_FAILED: return fetchSavedCandidatesFailed(state, action);
        default: return state;
    }
}

export default reducer;