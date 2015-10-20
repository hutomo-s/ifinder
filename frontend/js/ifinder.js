/** @jsx React.DOM */

var AgentRow = React.createClass({
    render: function() {
        var agent_name = this.props.agent.agent_name;
        var agent_phone = this.props.agent.agent_phone;
        return(
        	<div className="share mrl">
            <ul>
                <li><span className="fui-user"></span> {agent_name}</li>
                <li><span className="fui-mail"></span> {agent_phone}</li>
            </ul>
            <br />
            </div>
        );
    }
});

var AgentResults = React.createClass({

    getInitialState: function() {
        return {
            agents: [],
            search_uri: ''
        };
    },


    loadAgentsFromServer: function() {
        $.ajax({
          url: this.props.search_uri,
          dataType: 'json',
          cache: false,
          success: function(data) {
            this.setState({agents: data});
            console.log("sukses" + this.props.search_uri);
            console.log(data);
          }.bind(this),
          error: function(xhr, status, err) {
            console.error(status + " :: " + this.props.search_uri);
            //console.error(this.props.url, status, err.toString());
          }.bind(this)
        });
      },

  
    componentWillReceiveProps: function(event) {
        var search_uri = this.props.search_uri;

        this.setState({
            search_uri: search_uri,
            area_name: this.props.area_name
        });

        //console.log("Clicked: "+ this.props.search_uri);
        
        if(search_uri != ""){
            this.loadAgentsFromServer();
        
        }
    
    },


    render: function() {
        //var agents = [];
              
        var agent_rows = [];

        this.state.agents.forEach(function(agent) {
            // insensitive case
            agent_rows.push(<AgentRow agent={agent} key={agent.id} />);

        
        }.bind(this));
        
        return (
            <div>
               <strong>Area: {this.state.area_name}</strong>
                    {agent_rows}
           </div>
            );
  
    }
    

});

var AreaRow = React.createClass({

   handleClick: function(event) {
     //this.setState({liked: !this.state.liked});
     // !!!!! be careful in this index
     // callbackParent(url, area_name)
     this.props.callbackParent(this.props.area.area_url, this.props.area.area_name);
     //console.log("Udah diklik neeh"+ this.props.area.area_url + this.props.area.area_name);
    },
    render: function() {
        var name = this.props.area.area_name;
        return (
            <tr>
                <td><a onClick={this.handleClick}>{name}</a></td>
            </tr>
        );
    }
});

var AreaList = React.createClass({
    getInitialState: function() {
        return { 
            search_uri: '' 
        };
    },
    onChildChanged: function(uri, area_name) {
        this.setState(
            { 
                search_uri: uri,
                area_name: area_name 
            }
        );
        //console.log("New state"+ uri);
    },

    render: function() {
        console.log(this.props);
        
        // initialize value
        var rows = [];
        var search_uri = this.state.search_uri;
        var area_name = this.state.area_name;
        
        var results = <AgentResults search_uri={search_uri} area_name={area_name} />;
        this.props.areas.forEach(function(area) {
            // insensitive case
            var searchmessage = "";
            var lowercase_area = area.area_name.toLowerCase();
            var lowercase_filtertext = this.props.filterText.toLowerCase();

            if (lowercase_area.indexOf(lowercase_filtertext) === -1) {
                searchmessage = "No Matching Results";
                return;
            }

            rows.push(<AreaRow area={area} key={area.id} callbackParent={this.onChildChanged} />);

        
        }.bind(this));

    
        // <div>
        //     <AgentResults search_uri={this.state.search_uri} />
        // </div>


        if(this.props.showAreaList) {
            return (
            <div>
                <div className="col-xs-6">
                <table>
                    <thead>
                        <tr>
                            <th>Search Results</th>
                       </tr>
                    </thead>
                    <tbody>
                        {rows}
                    </tbody>
                </table>
                </div>
                <div className="col-xs-6">
                {results}
                </div>
            </div>
            );
        }
        else
        {
            return(
                <div>Please Enter Something</div>
            );
        }
    }
});

var SearchBar = React.createClass({
    handleChange: function() {
        this.props.onUserInput(
            this.refs.filterTextInput.getDOMNode().value
        );
    },
    render: function() {
        return (
            <div className="col-xs-12">

            <h3 className="demo-panel-title">Find Our Nearest Agent</h3>
            <div className="form-group">
            	<input type="text" value={this.props.filterText} 
            	placeholder="Please enter your area" 
            	className="form-control" 
            	ref="filterTextInput"
                onChange={this.handleChange}
                    />
                
            </div>
            </div>
        );
    }
});

var FilterableAreaTable = React.createClass({
    getInitialState: function() {
        return {
            areas: [],
            filterText: '',
            showAreaList: false,
        };
    },

    componentDidMount: function() {
        $.ajax({
            url: this.props.source,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({areas: data});
                console.log(data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.source, status, err.toString());
            }.bind(this)
        });
    },


    handleUserInput: function(filterText) {
        this.setState({
            filterText: filterText,
        });

        if(filterText != "")
        {
            this.setState({showAreaList: true});
        }
        else
        {
            this.setState({showAreaList: false});
        }
    },

    render: function() {
        return (
            <div>
                <SearchBar
                    filterText={this.state.filterText}
                    onUserInput={this.handleUserInput}
                />
                <AreaList
                    areas={this.state.areas}
                    filterText={this.state.filterText}
                    showAreaList={this.state.showAreaList}
                />
            </div>
        );
    }
});