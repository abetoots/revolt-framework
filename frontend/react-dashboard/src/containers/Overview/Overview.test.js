import React from 'react';

import { configure, shallow } from 'enzyme';
import Adapter from 'enzyme-adapter-react-16';

import { Overview } from './Overview';
import Spinner from '../../components/UI/Spinner/Spinner';

configure({ adapter: new Adapter() });

describe('<Overview />', () => {
    let wrapper;
    beforeEach(() => {
        wrapper = shallow(<Overview fetchRecentJobsOnMount={() => { }} fetchSavedCandidatesOnMount={() => { }} fetchJobsOnMount={() => { }} />);
    })

    it('should render 2 <Spinner /> components when not authenticated', () => {
        expect(wrapper.find(Spinner)).toHaveLength(2);
    });


});
