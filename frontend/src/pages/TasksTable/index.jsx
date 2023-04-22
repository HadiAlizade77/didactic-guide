import React, { useState, useEffect } from "react";
import axios from "axios";
import { Button, Card, CardBody, Container, Table } from "reactstrap";
import "react-datepicker/dist/react-datepicker.css";

//Import Breadcrumb
import Breadcrumbs from "../../components/Common/Breadcrumb";
import { Link } from "react-router-dom";

const Tasks = () => {
  const [tasks, setTasks] = useState([]);

  useEffect(() => {
    const fetchTasks = async () => {
      try {
        const response = await axios.get("/api/tasks");
        setTasks(response.data);
      } catch (error) {
        console.error("Error fetching tasks:", error);
      }
    };
    fetchTasks();
  }, []);

  return (
    <>
      <style type="text/css">
        {`
    .card {
      height:80vh!important;
    }
    `}
      </style>
      <div className="page-content">
        <Container fluid={true}>
          <Breadcrumbs title="Forms" breadcrumbItem="Form Advanced" />

          <Row className="mb-2">
            <Col sm="4">
              <div className="search-box me-2 mb-2 d-inline-block">
                <div className="position-relative">
                  <SearchBar {...toolkitProps.searchProps} />
                  <i className="bx bx-search-alt search-icon" />
                </div>
              </div>
            </Col>
            <Col sm="8">
              <div className="text-sm-end">
                <Button
                  type="button"
                  color="success"
                  className="btn-rounded mb-2 me-2"
                  onClick={this.handleOrderClicks}
                >
                  <i className="mdi mdi-plus me-1" />
                  Add New Order
                </Button>
              </div>
            </Col>
            <Col lg="12">
              <Card style={{ height: "100vh !important" }}>
                <CardBody>
                  <h4 className="card-title">Tasks</h4>

                  <Table striped bordered hover>
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Industry</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>Keyword</th>
                        <th>Board</th>
                      </tr>
                    </thead>
                    <tbody>
                      {tasks.map((task, index) => (
                        <tr key={task.id}>
                          <td>{index + 1}</td>
                          <td>{task.industry}</td>
                          <td>{task.country}</td>
                          <td>{task.state}</td>
                          <td>{task.keyword}</td>
                          <td>{task.board}</td>
                        </tr>
                      ))}
                    </tbody>
                  </Table>
                </CardBody>
              </Card>
            </Col>
          </Row>

          {/* <Row>
            <Col lg="12">
              <Card>
                <CardBody>
                  <h4 className="card-title">Bootstrap MaxLength</h4>
                  <p className="card-title-desc">
                    This plugin integrates by default with Twitter bootstrap
                    using badges to display the maximum lenght of the field
                    where the user is inserting text.{" "}
                  </p>
                  <Label>Default usage</Label>
                  <p className="text-muted m-b-15">
                    The badge will show up by default when the remaining chars
                    are 10 or less:
                  </p>
                  <Input
                    type="text"
                    maxLength="25"
                    name="defaultconfig"
                    onChange={threshholdDefault}
                    id="defaultconfig"
                  />
                  {state.disDefault ? (
                    <span className="badgecount badge bg-success">
                      {state.threshholdDefault} / 25{" "}
                    </span>
                  ) : null}

                  <div className="mt-3">
                    <Label>Threshold value</Label>
                    <p className="text-muted m-b-15">
                      Do you want the badge to show up when there are 20 chars
                      or less? Use the <code>threshold</code> option:
                    </p>
                    <Input
                      type="text"
                      maxLength="25"
                      onChange={threshholdchange}
                      name="thresholdconfig"
                      id="thresholdconfig"
                    />
                    {state.disthresh ? (
                      <span className="badgecount badge bg-success">
                        {state.threshholdcount} / 25{" "}
                      </span>
                    ) : null}
                  </div>

                  <div className="mt-3">
                    <Label>All the options</Label>
                    <p className="text-muted m-b-15">
                      Please note: if the <code>alwaysShow</code> option is
                      enabled, the <code>threshold</code> option is ignored.
                    </p>
                    <Input
                      type="text"
                      maxLength="25"
                      onChange={optionchange}
                      name="alloptions"
                      id="alloptions"
                    />
                    {state.disbadge ? (
                      <span className="badgecount">
                        You Types{" "}
                        <span className="badge bg-success">
                          {state.optioncount}
                        </span>{" "}
                        out of <span className="badge bg-success">25</span>{" "}
                        chars available
                      </span>
                    ) : null}
                  </div>

                  <div className="mt-3">
                    <Label>Position</Label>
                    <p className="text-muted m-b-15">
                      All you need to do is specify the <code>placement</code>{" "}
                      option, with one of those strings. If none is specified,
                      the positioning will be defauted to &apos;bottom&lsquo;.
                    </p>
                    <Input
                      type="text"
                      maxLength="25"
                      onChange={placementchange}
                      name="placement"
                      id="placement"
                    />
                    {state.placementbadge ? (
                      <span
                        style={{ float: "right" }}
                        className="badgecount badge bg-success"
                      >
                        {state.placementcount} / 25{" "}
                      </span>
                    ) : null}
                  </div>

                  <div className="mt-3">
                    <Label>Textarea</Label>
                    <p className="text-muted m-b-15">
                      Bootstrap maxlength supports textarea as well as inputs.
                      Even on old IE.
                    </p>
                    <Input
                      type="textarea"
                      id="textarea"
                      onChange={textareachange}
                      maxLength="225"
                      rows="3"
                      placeholder="This textarea has a limit of 225 chars."
                    />
                    {state.textareabadge ? (
                      <span className="badgecount badge bg-success">
                        {" "}
                        {state.textcount} / 225{" "}
                      </span>
                    ) : null}
                  </div>
                </CardBody>
              </Card>
            </Col>
          </Row> */}
        </Container>
      </div>
    </>
  );
};

export default Tasks;
