import React from 'react';
import PropTypes from 'prop-types';

class Pagination extends React.Component {
	
	constructor(props) {
		super(props);
		this.linkHandler = this.linkHandler.bind(this);
	}
	
	linkHandler(e) {
		e.preventDefault();
		this.props.pager(e.target.getAttribute('data-page'));
	}
	
	render() {
		const pages = Math.ceil(this.props.jobsCount()/parseInt(this.props.perPage));
		let pager = [];
		for (let i = 1; i <= pages; i++) {
			pager.push(<a href="#" data-page={i} className={(this.props.currentPage == i) ? 'active' : ''} onClick={this.linkHandler}>{i}</a>);
		}
		let output = '';
		if (pager.length > 1) {
			output = <div className="jt-pagination"><span>Pages: </span><div className="pager-links">{pager}</div></div>;
		}
		return (
			<React.Fragment>
				{output}
			</React.Fragment>
		);
	}
}

Pagination.propTypes = {
  perPage: PropTypes.string.isRequired,
  pager: PropTypes.func.isRequired,
  jobsCount: PropTypes.func.isRequired
};

export default Pagination;