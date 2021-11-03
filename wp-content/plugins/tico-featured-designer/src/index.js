import "./index.scss";
import { useSelect } from "@wordpress/data";
import { useState, useEffect } from "react";
import apiFetch from "@wordpress/api-fetch";

wp.blocks.registerBlockType("ticoplugin/featured-designer", {
  title: "Designer Callout",
  description: "Include a short description and link to a designer of your choice",
  icon: "welcome-learn-more",
  category: "common",
  attributes: {
    designerId: { type: "string" }
  },
  edit: EditComponent,
  save: () => { return null }
})

function EditComponent(props) {
  const [thePreview, setThePreview] = useState("");

  useEffect(() => {
    if (props.attributes.designerId) {
      updateTheMeta();
      async function go() {
        const response = await apiFetch({
          path: `/featuredDesigner/v1/getHTML?designerId=${props.attributes.designerId}`,
          method: "GET"
        });
        setThePreview(response);
      }
      go();
    }
  }, [props.attributes.designerId]);

  useEffect(() => {
    return () => {
      updateTheMeta();
    }
  }, []);

  function updateTheMeta() {
    const designersForMeta = wp.data.select("core/block-editor")
      .getBlocks()
      .filter((x) => x.name == "ticoplugin/featured-designer")
      .map((x) => x.attributes.designerId)
      .filter((x, index, arr) => {
        return arr.indexOf(x) == index;
      });
    console.log(designersForMeta);
    wp.data.dispatch("core/editor").editPost({ meta: {featureddesigner: designersForMeta} })
  }

  const allDesigners = useSelect((select) => {
    return select("core").getEntityRecords("postType", "designer", {per_page: -1});
  })
  console.log(allDesigners);

  if (allDesigners == undefined) return <p>Loading...</p>;

  return (
    <div className="featured-professor-wrapper">
      <div className="professor-select-container">
        <select onChange={(e) => props.setAttributes({designerId: e.target.value})}>
          <option value="">Select a designer</option>
          {allDesigners.map((designer) => {
            return (
              <option value={designer.id} selected={props.attributes.designerId == designer.id}>{designer.title.rendered}</option>
            )
          })}
          
        </select>
      </div>
      <div dangerouslySetInnerHTML={{__html: thePreview}}></div>
    </div>
  )
}