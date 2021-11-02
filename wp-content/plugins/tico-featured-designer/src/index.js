import "./index.scss";
import { useSelect } from "@wordpress/data";

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
      <div>
        The HTML preview of the selected designer will appear here.
      </div>
    </div>
  )
}