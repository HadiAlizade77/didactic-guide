import { Button, Card, CardBody, Container, Table, Row, Col } from "reactstrap";
//Import Breadcrumb
import Breadcrumbs from "../../components/Common/Breadcrumb";
import { Link } from "react-router-dom";
import React, { useState, useEffect } from "react";
import axios from "axios";

const CsvTable = () => {
  const [csvFiles, setCsvFiles] = useState([]);

  useEffect(() => {
    axios
      .get("/csv")
      .then((response) => {
        setCsvFiles(response.data);
      })
      .catch((error) => {
        console.log(error);
      });
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
            <Col lg="12">
              <Card style={{ height: "100vh !important" }}>
                <CardBody>
                  <h4 className="card-title">files</h4>

                  <Table striped bordered hover>
                    <thead>
                      <tr>
                        <th>File Name</th>
                        <th>Download</th>
                      </tr>
                    </thead>
                    <tbody>
                      {csvFiles.map((file) => (
                        <tr key={file.name}>
                          <td>{file.name}</td>
                          <td>
                            <Button href={file.link} download>
                              Download
                            </Button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </Table>
                </CardBody>
              </Card>
            </Col>
          </Row>
        </Container>
      </div>
    </>
  );
};

export default CsvTable;
