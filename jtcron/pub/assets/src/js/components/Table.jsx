import React from 'react';
import PropTypes from 'prop-types';
import Row from './Row.jsx';
import axios from 'axios';
import Buttons from "./Buttons.jsx";

class Table extends React.Component {

    constructor(props) {
        super(props);
        this._baseUrl = window._baseUrl;
		this.perPage = parseInt(props.perPage);
        this.state = {
            jobs: [],
			page: 1
        };
		this.deleteRecords = this.deleteRecords.bind(this);
		this.loadPage = this.loadPage.bind(this);
		this.getJobsCount = this.getJobsCount.bind(this);
    }

    componentDidMount() {
        this.loadList();
    }
	
	loadList() {
		axios.get(
            this._baseUrl+'cronjob'
        ).then((response) => {
            if (response.status === 200) {
				const {data} = response;
                this.setState({jobs : data});
            } else {
                //console.log('Can\'t load jobs list');
            }
        }).then((error) => {
           console.log('Can\'t load jobs list');
           console.log(error);
        });
	}
	
	loadPage(number) {
		this.setState({page: number});
	}
	
	getJobsCount() {
		return this.state.jobs.length;
	}
	
	getAllIds() {
		const output = [];
		this.state.jobs.forEach(one => {
			output.push({value: one.id});
		});
		return output;
	}
	
	deleteRecords(ids) {
		const _ids = [];
		let _inputs = null;
		if (ids === 'selected') {
			_inputs = document.querySelectorAll('[name="id"]:checked');
		} else if (ids === 'all') {
			_inputs = this.getAllIds();
		}
		
		if (_inputs) {
			[].forEach.call(_inputs, (one) => {
				_ids.push(one.value);
			});	
			axios.delete(this._baseUrl+'cronjob', {params: {records: _ids}})
				.then((response) => {
					this.loadList();
			})
		} else {
			alert('You should choose some jobs');
		}
	}


    render() {
		const start = (this.state.page-1)*this.perPage,
			  end = start + this.perPage;
		const toRender = this.state.jobs.slice(start, end);
        return ( 
			<div>
				<table className="wp-list-table widefat fixed striped posts jobs_table">
					<tr>
						<th></th>
						<th>id</th>
						<th>Name</th>
						<th>Status</th>
						<th>Message</th>
						<th>Time</th>
					</tr>
					{toRender.map((job, i) => { return <Row job={job} key={i} /> })}
				</table>
				<Buttons deleteHandler={this.deleteRecords} perPage={this.perPage} pager={this.loadPage} currentPage={this.state.page} jobsCount={this.getJobsCount} />
			</div>
        );
    }
}

Table.propTypes = {
  perPage: PropTypes.string.isRequired
};

export default Table;