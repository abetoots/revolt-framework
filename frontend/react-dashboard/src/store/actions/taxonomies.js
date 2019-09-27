import * as actionTypes from './actionTypes';
import axios from '../../axios-instance';

export const fetchTaxonomiesStart = () => {
    return {
        type: actionTypes.FETCH_TAXONOMIES_START
    }
}

export const fetchTaxonomiesSuccess = taxonomiesData => {
    return {
        type: actionTypes.FETCH_TAXONOMIES_SUCCESS,
        taxonomies: taxonomiesData
    }
}

export const fetchTaxonomiesFailed = error => {
    return {
        type: actionTypes.FETCH_TAXONOMIES_FAILED,
        error: error
    }
}

export const fetchTaxonomies = () => {
    return async dispatch => {
        dispatch(fetchTaxonomiesStart());
        try {
            const arrayTaxonomy = await Promise.all([
                axios.get('/wp-json/wp/v2/job_categories'),
                axios.get('/wp-json/wp/v2/employment_types'),
                axios.get('/wp-json/wp/v2/premium_packages')
            ])
                .then(response => {
                    //lets only get the data
                    return response.map(res => res.data);
                })
            /**
             * Returns an object containing arrays that are mapped to return only the id, name, slug, and taxonomy
             * Example:
             * @returns {
             * job_categories : [{id: value, name: value, slug: value, taxonomy: 'job_categories'}],
             * employment_types: [{id: value, name: value, slug: value, taxonomy: 'employment_types'}]
             * }
             */
            const finalObj = {};
            arrayTaxonomy.forEach(taxonomyArray => {
                const taxonomyName = taxonomyArray[0].taxonomy;
                const mappedArray = taxonomyArray.map(taxonomy => {
                    return {
                        id: taxonomy.id,
                        name: taxonomy.name,
                        slug: taxonomy.slug,
                        taxonomy: taxonomy.taxonomy
                    };
                })
                finalObj[taxonomyName] = mappedArray;
            });
            dispatch(fetchTaxonomiesSuccess(finalObj));
        } catch (err) {
            console.log(err);
            dispatch(fetchTaxonomiesFailed(err));
        }
    }
}