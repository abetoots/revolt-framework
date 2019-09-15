import * as actionTypes from '../actions/actionTypes';
import { updateObject } from '../../utility/utility';

const initialState = {
    candidates: [],
    savedCands: [],
    loadingCandidates: false,
    loadingSavedCandidates: false,
    loadedCandidates: false,
    loadedSavedCandidates: false,
    errorCandidates: null,
    errorSavedCandidates: null
}

const fetchCandidatesSuccess = (state, action) => {
    return updateObject(state, {
        candidates: action.candidates,
        loadingCandidates: false,
        loadedCandidates: true
    })
}

const fetchCandidatesFailed = (state, action) => {
    return updateObject(state, {
        loadingCandidates: false,
        errorCandidates: action.error
    })
}

const fetchSavedCandidatesSuccess = (state, action) => {
    return updateObject(state, {
        savedCands: action.savedCandidates,
        loadingSavedCandidates: false,
        loadedSavedCandidates: true
    })
}

const fetchSavedCandidatesFailed = (state, action) => {
    return updateObject(state, {
        loading: false,
        errorSavedCandidates: action.error
    })
}

const reducer = (state = initialState, action) => {
    switch (action.type) {
        case actionTypes.FETCH_CANDIDATES_START: return updateObject(state, { loadingCandidates: true });
        case actionTypes.FETCH_CANDIDATES_SUCCESS: return fetchCandidatesSuccess(state, action);
        case actionTypes.FETCH_CANDIDATES_FAILED: return fetchCandidatesFailed(state, action);
        case actionTypes.FETCH_SAVED_CANDIDATES_START: return updateObject(state, { loadingSavedCandidates: true });
        case actionTypes.FETCH_SAVED_CANDIDATES_SUCCESS: return fetchSavedCandidatesSuccess(state, action);
        case actionTypes.FETCH_SAVED_CANDIDATES_FAILED: return fetchSavedCandidatesFailed(state, action);
        default: return state;
    }
}

export default reducer;