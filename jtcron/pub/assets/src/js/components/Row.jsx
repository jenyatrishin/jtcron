import React from 'react';
import PropTypes from 'prop-types';

const Row = (props) => {
	
	const checkRow = (e) => {
		let _row = null;
        if (e.target.localName === 'input') {
            return;
        }
        if (e.target.localName === 'tr') {
            _row = e.target;
        } else if (e.target.localName === 'td') {
            _row = e.target.parentNode;
        }
        _row.querySelector('input').click();	
	};
	
	return (
            <tr onClick={(e) => checkRow(e)} className={props.job.status}>
                <td><input type="checkbox" name="id" value={props.job.id} /></td>
                <td>{props.job.id}</td>
                <td>{props.job.name}</td>
                <td>{props.job.status}</td>
                <td>{props.job.message}</td>
                <td>{props.job.time}</td>
            </tr>
        );
};

Row.propTypes = {
	job: PropTypes.object
};

export default Row;