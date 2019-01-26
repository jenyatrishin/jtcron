import React from 'react';
import Pagination from "./Pagination";
import PropTypes from 'prop-types';

const Buttons = (props) => {
	
	return (
			<div className="buttons">
				<button type="button" onClick={(e) => { props.deleteHandler('selected') }} className="page-title-action">Delete selected</button>
				<button type="button" onClick={(e) => { props.deleteHandler('all') }} className="page-title-action">Delete all</button>
				<Pagination pager={props.pager} currentPage={props.currentPage} jobsCount={props.jobsCount} perPage={props.perPage} />
			</div>
	);
};

Buttons.propTypes = {
	deleteHandler: PropTypes.func,
	perPage: PropTypes.string.isRequired,
	pager: PropTypes.func.isRequired,
	jobsCount: PropTypes.func.isRequired
};

export default Buttons;