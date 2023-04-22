import React, { Component } from "react";
import {
  Button,
  Card,
  CardBody,
  Col,
  Container,
  Form,
  FormGroup,
  Input,
  InputGroup,
  Label,
  Row,
} from "reactstrap";

import "@vtaits/react-color-picker/dist/index.css";
import "react-datepicker/dist/react-datepicker.css";
import Select from "react-select";
import AsyncSelect from "react-select/async";
import makeAnimated from "react-select/animated";
import "flatpickr/dist/themes/material_blue.css";
//Import Breadcrumb
import Breadcrumbs from "../../components/Common/Breadcrumb";
import axios from "axios";
import { Link } from "react-router-dom";
import { Country, State, City } from "country-state-city";
const countryOptions = Country.getAllCountries();
const cityOptions = City.getAllCities();
const stateOptions = State.getAllStates();
const animatedComponents = makeAnimated();

const boardsList = [
  {
    label: "Websites",
    options: [{ label: "Linkedin", value: "linkedin" }],
  },
];
const industries = [
  {
    label: "Select",
    options: [
      {
        label: "Education",
        value: "Education",
      },
      {
        label: "Education Management",
        value: "Education Management",
      },
      {
        label: "E-Learning",
        value: "E-Learning",
      },
      {
        label: "Higher Education",
        value: "Higher Education",
      },
      {
        label: "Primary/Secondary Education",
        value: "Primary/Secondary Education",
      },
      {
        label: "Research",
        value: "Research",
      },
      {
        label: "Construction",
        value: "Construction",
      },
      {
        label: "Building Materials",
        value: "Building Materials",
      },
      {
        label: "Civil Engineering",
        value: "Civil Engineering",
      },
      {
        label: "Construction",
        value: "Construction",
      },
      {
        label: "Design",
        value: "Design",
      },
      {
        label: "Architecture & Planning",
        value: "Architecture & Planning",
      },
      {
        label: "Design",
        value: "Design",
      },
      {
        label: "Graphic Design",
        value: "Graphic Design",
      },
      {
        label: "Corporate Services",
        value: "Corporate Services",
      },
      {
        label: "Accounting",
        value: "Accounting",
      },
      {
        label: "Business Supplies & Equipment",
        value: "Business Supplies & Equipment",
      },
      {
        label: "Environmental Services",
        value: "Environmental Services",
      },
      {
        label: "Events Services",
        value: "Events Services",
      },
      {
        label: "Executive Office",
        value: "Executive Office",
      },
      {
        label: "Facilities Services",
        value: "Facilities Services",
      },
      {
        label: "Human Resources",
        value: "Human Resources",
      },
      {
        label: "Information Services",
        value: "Information Services",
      },
      {
        label: "Management Consulting",
        value: "Management Consulting",
      },
      {
        label: "Outsourcing/Offshoring",
        value: "Outsourcing/Offshoring",
      },
      {
        label: "Professional Training & Coaching",
        value: "Professional Training & Coaching",
      },
      {
        label: "Security & Investigations",
        value: "Security & Investigations",
      },
      {
        label: "Staffing & Recruiting",
        value: "Staffing & Recruiting",
      },
      {
        label: "Retail",
        value: "Retail",
      },
      {
        label: "Retail",
        value: "Retail",
      },
      {
        label: "Supermarkets",
        value: "Supermarkets",
      },
      {
        label: "Wholesale",
        value: "Wholesale",
      },
      {
        label: "Energy & Mining",
        value: "Energy & Mining",
      },
      {
        label: "Mining & Metals",
        value: "Mining & Metals",
      },
      {
        label: "Oil & Energy",
        value: "Oil & Energy",
      },
      {
        label: "Utilities",
        value: "Utilities",
      },
      {
        label: "Manufacturing",
        value: "Manufacturing",
      },
      {
        label: "Automotive",
        value: "Automotive",
      },
      {
        label: "Aviation & Aerospace",
        value: "Aviation & Aerospace",
      },
      {
        label: "Chemicals",
        value: "Chemicals",
      },
      {
        label: "Defense & Space",
        value: "Defense & Space",
      },
      {
        label: "Electrical & Electronic Manufacturing",
        value: "Electrical & Electronic Manufacturing",
      },
      {
        label: "Food Production",
        value: "Food Production",
      },
      {
        label: "Glass, Ceramics & Concrete",
        value: "Glass, Ceramics & Concrete",
      },
      {
        label: "Industrial Automation",
        value: "Industrial Automation",
      },
      {
        label: "Machinery",
        value: "Machinery",
      },
      {
        label: "Mechanical or Industrial Engineering",
        value: "Mechanical or Industrial Engineering",
      },
      {
        label: "Packaging & Containers",
        value: "Packaging & Containers",
      },
      {
        label: "Paper & Forest Products",
        value: "Paper & Forest Products",
      },
      {
        label: "Plastics",
        value: "Plastics",
      },
      {
        label: "Railroad Manufacture",
        value: "Railroad Manufacture",
      },
      {
        label: "Renewables & Environment",
        value: "Renewables & Environment",
      },
      {
        label: "Shipbuilding",
        value: "Shipbuilding",
      },
      {
        label: "Textiles",
        value: "Textiles",
      },
      {
        label: "Finance",
        value: "Finance",
      },
      {
        label: "Banking",
        value: "Banking",
      },
      {
        label: "Capital Markets",
        value: "Capital Markets",
      },
      {
        label: "Financial Services",
        value: "Financial Services",
      },
      {
        label: "Insurance",
        value: "Insurance",
      },
      {
        label: "Investment Banking",
        value: "Investment Banking",
      },
      {
        label: "Investment Management",
        value: "Investment Management",
      },
      {
        label: "Venture Capital & Private Equity",
        value: "Venture Capital & Private Equity",
      },
      {
        label: "Recreation & Travel",
        value: "Recreation & Travel",
      },
      {
        label: "Airlines/Aviation",
        value: "Airlines/Aviation",
      },
      {
        label: "Gambling & Casinos",
        value: "Gambling & Casinos",
      },
      {
        label: "Hospitality",
        value: "Hospitality",
      },
      {
        label: "Leisure, Travel & Tourism",
        value: "Leisure, Travel & Tourism",
      },
      {
        label: "Restaurants",
        value: "Restaurants",
      },
      {
        label: "Recreational Facilities & Services",
        value: "Recreational Facilities & Services",
      },
      {
        label: "Sports",
        value: "Sports",
      },
      {
        label: "Arts",
        value: "Arts",
      },
      {
        label: "Arts & Crafts",
        value: "Arts & Crafts",
      },
      {
        label: "Fine Art",
        value: "Fine Art",
      },
      {
        label: "Performing Arts",
        value: "Performing Arts",
      },
      {
        label: "Photography",
        value: "Photography",
      },
      {
        label: "Health Care",
        value: "Health Care",
      },
      {
        label: "Biotechnology",
        value: "Biotechnology",
      },
      {
        label: "Hospital & Health Care",
        value: "Hospital & Health Care",
      },
      {
        label: "Medical Device",
        value: "Medical Device",
      },
      {
        label: "Medical Practice",
        value: "Medical Practice",
      },
      {
        label: "Mental Health Care",
        value: "Mental Health Care",
      },
      {
        label: "Pharmaceuticals",
        value: "Pharmaceuticals",
      },
      {
        label: "Veterinary",
        value: "Veterinary",
      },
      {
        label: "Hardware & Networking",
        value: "Hardware & Networking",
      },
      {
        label: "Computer Hardware",
        value: "Computer Hardware",
      },
      {
        label: "Computer Networking",
        value: "Computer Networking",
      },
      {
        label: "Nanotechnologie",
        value: "Nanotechnologie",
      },
      {
        label: "Semiconductors",
        value: "Semiconductors",
      },
      {
        label: "Telecommunications",
        value: "Telecommunications",
      },
      {
        label: "Wireless",
        value: "Wireless",
      },
      {
        label: "Real Estate",
        value: "Real Estate",
      },
      {
        label: "Commercial Real Estate",
        value: "Commercial Real Estate",
      },
      {
        label: "Real Estate",
        value: "Real Estate",
      },
      {
        label: "Legal",
        value: "Legal",
      },
      {
        label: "Alternative Dispute Resolution",
        value: "Alternative Dispute Resolution",
      },
      {
        label: "Law Practice",
        value: "Law Practice",
      },
      {
        label: "Legal Services",
        value: "Legal Services",
      },
      {
        label: "Consumer Goods",
        value: "Consumer Goods",
      },
      {
        label: "Apparel & Fashion",
        value: "Apparel & Fashion",
      },
      {
        label: "Consumer Electronics",
        value: "Consumer Electronics",
      },
      {
        label: "Consumer Goods",
        value: "Consumer Goods",
      },
      {
        label: "Consumer Services",
        value: "Consumer Services",
      },
      {
        label: "Cosmetics",
        value: "Cosmetics",
      },
      {
        label: "Food & Beverages",
        value: "Food & Beverages",
      },
      {
        label: "Furniture",
        value: "Furniture",
      },
      {
        label: "Luxury Goods & Jewelry",
        value: "Luxury Goods & Jewelry",
      },
      {
        label: "Sporting Goods",
        value: "Sporting Goods",
      },
      {
        label: "Tobacco",
        value: "Tobacco",
      },
      {
        label: "Wine and Spirits",
        value: "Wine and Spirits",
      },
      {
        label: "Agriculture",
        value: "Agriculture",
      },
      {
        label: "Dairy",
        value: "Dairy",
      },
      {
        label: "Farming",
        value: "Farming",
      },
      {
        label: "Fishery",
        value: "Fishery",
      },
      {
        label: "Ranching",
        value: "Ranching",
      },
      {
        label: "Media & Communications",
        value: "Media & Communications",
      },
      {
        label: "Market Research",
        value: "Market Research",
      },
      {
        label: "Marketing & Advertising",
        value: "Marketing & Advertising",
      },
      {
        label: "Newspapers",
        value: "Newspapers",
      },
      {
        label: "Online Media",
        value: "Online Media",
      },
      {
        label: "Printing",
        value: "Printing",
      },
      {
        label: "Public Relations & Communications",
        value: "Public Relations & Communications",
      },
      {
        label: "Publishing",
        value: "Publishing",
      },
      {
        label: "Translation & Localization",
        value: "Translation & Localization",
      },
      {
        label: "Writing & Editing",
        value: "Writing & Editing",
      },
      {
        label: "Nonprofit",
        value: "Nonprofit",
      },
      {
        label: "Civic & Social Organization",
        value: "Civic & Social Organization",
      },
      {
        label: "Fundraising",
        value: "Fundraising",
      },
      {
        label: "Individual & Family Services",
        value: "Individual & Family Services",
      },
      {
        label: "International Trade & Development",
        value: "International Trade & Development",
      },
      {
        label: "Libraries",
        value: "Libraries",
      },
      {
        label: "Museums & Institutions",
        value: "Museums & Institutions",
      },
      {
        label: "Non-Profit Organization Management",
        value: "Non-Profit Organization Management",
      },
      {
        label: "Philanthropy",
        value: "Philanthropy",
      },
      {
        label: "Program Development",
        value: "Program Development",
      },
      {
        label: "Religious Institutions",
        value: "Religious Institutions",
      },
      {
        label: "Think Tanks",
        value: "Think Tanks",
      },
      {
        label: "Software & IT Services",
        value: "Software & IT Services",
      },
      {
        label: "Computer & Network Security",
        value: "Computer & Network Security",
      },
      {
        label: "Computer Software",
        value: "Computer Software",
      },
      {
        label: "Information Technology & Services",
        value: "Information Technology & Services",
      },
      {
        label: "Internet",
        value: "Internet",
      },
      {
        label: "Transportation & Logistics",
        value: "Transportation & Logistics",
      },
      {
        label: "Import & Export",
        value: "Import & Export",
      },
      {
        label: "Logistics & Supply Chain",
        value: "Logistics & Supply Chain",
      },
      {
        label: "Maritime",
        value: "Maritime",
      },
      {
        label: "Package/Freight Delivery",
        value: "Package/Freight Delivery",
      },
      {
        label: "Transportation/Trucking/Railroad",
        value: "Transportation/Trucking/Railroad",
      },
      {
        label: "Warehousing",
        value: "Warehousing",
      },
      {
        label: "Entertainment",
        value: "Entertainment",
      },
      {
        label: "Animation",
        value: "Animation",
      },
      {
        label: "Broadcast Media",
        value: "Broadcast Media",
      },
      {
        label: "Computer Games",
        value: "Computer Games",
      },
      {
        label: "Entertainment",
        value: "Entertainment",
      },
      {
        label: "Media Production",
        value: "Media Production",
      },
      {
        label: "Mobile Games",
        value: "Mobile Games",
      },
      {
        label: "Motion Pictures & Film",
        value: "Motion Pictures & Film",
      },
      {
        label: "Music",
        value: "Music",
      },
      {
        label: "Wellness & Fitness",
        value: "Wellness & Fitness",
      },
      {
        label: "Alternative Medicine",
        value: "Alternative Medicine",
      },
      {
        label: "Health, Wellness & Fitness",
        value: "Health, Wellness & Fitness",
      },
      {
        label: "Public Safety",
        value: "Public Safety",
      },
      {
        label: "Law Enforcement",
        value: "Law Enforcement",
      },
      {
        label: "Military",
        value: "Military",
      },
      {
        label: "Public Safety",
        value: "Public Safety",
      },
      {
        label: "Public Administration",
        value: "Public Administration",
      },
      {
        label: "Government Administration",
        value: "Government Administration",
      },
      {
        label: "Government Relations",
        value: "Government Relations",
      },
      {
        label: "International Affairs",
        value: "International Affairs",
      },
      {
        label: "Judiciary",
        value: "Judiciary",
      },
      {
        label: "Legislative Office",
        value: "Legislative Office",
      },
      {
        label: "Political Organization",
        value: "Political Organization",
      },
      {
        label: "Public Policy",
        value: "Public Policy",
      },
    ],
  },
];

const Dashboard = () => {
  const [country, setCountry] = React.useState(null);
  const [state, setState] = React.useState(null);
  const [industry, setIndustry] = React.useState(null);
  const [board, setBoard] = React.useState(null);
  const [keyword, setKeyword] = React.useState("");
  const [stateOptions, setStateOptions] = React.useState([]);
  //Select
  const onCountryChange = (val) => {
    setCountry(val);
    setStateOptions(State.getStatesOfCountry(val.isoCode));
  };
  const handleSubmit = async (event) => {
    event.preventDefault();
    const payload = { industry, country, state, keyword, board };
    try {
      const response = await axios.post("http://api.bot.remotephase.com/api/tasks", payload);
      console.log("New task created:", response.data);
    } catch (error) {
      console.error("Error creating task:", error);
    }
  };
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

          <Row>
            <Col lg="12">
              <Card style={{ height: "100vh !important" }}>
                <CardBody>
                  <h4 className="card-title">Configure a New Task</h4>

                  <form>
                    <Row>
                      <Col lg="6">
                        <div className="mb-3 select2-container">
                          <Label>Board</Label>
                          <Select
                            value={board}
                            onChange={(v) => setBoard(v)}
                            options={boardsList}
                            classNamePrefix="select2-selection"
                          />
                        </div>
                        <div className="mb-3 select2-container">
                          <Label>Keyword</Label>
                          <Input
                            type="text"
                            maxLength="25"
                            placeholder="Keyword to search"
                            name="defaultconfig"
                            value={keyword}
                            onChange={(e) => setKeyword(e.target.value)}
                            id="defaultconfig"
                          />
                        </div>
                      </Col>

                      <Col lg="6">
                        <FormGroup className="mb-3 ajax-select select2-container">
                          <div className="mb-3 select2-container">
                            <Label>Country</Label>
                            <Select
                              value={country}
                              isMulti={false}
                              getOptionLabel={(i) => i.name}
                              onChange={onCountryChange}
                              options={countryOptions}
                              classNamePrefix="select2-selection"
                            />
                          </div>
                          <div className="mb-3 select2-container">
                            <Label>State</Label>
                            <Select
                              disabled
                              value={state}
                              isMulti={false}
                              getOptionLabel={(i) => i.name}
                              onChange={(v) => setState(v)}
                              options={stateOptions}
                              classNamePrefix="select2-selection"
                            />
                          </div>
                        </FormGroup>
                      </Col>
                      <Col lg="12">
                        <FormGroup className="mb-3 mt-3 mt-lg-0 templating-select select2-container">
                          <label className="control-label">Industry</label>
                          <Select
                            value={industry}
                            isMulti={true}
                            onChange={(v) => setIndustry(v)}
                            options={industries}
                            classNamePrefix="select2-selection"
                            closeMenuOnSelect={false}
                            components={animatedComponents}
                          />
                        </FormGroup>
                      </Col>
                    </Row>
                  </form>
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

export default Dashboard;
