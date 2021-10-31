import "./index.scss";
import { Flex, TextControl } from "@wordpress/components";
// alert("hello from js file");

// prettier-ignore
wp.blocks.registerBlockType("ticoplugin/tico-pay-attention", {
    title: "Are You Paying Attention?",
    icon: "smiley",
    category: "common",
    attributes: {
        skyColor: { type: "string" },
        grassColor: { type: "string" }
    },
    edit: EditComponent,
    save: (props) => { return null },
});

function EditComponent(props) {
  function updateSkyColor(e) {
    props.setAttributes({ skyColor: e.target.value });
  }

  function updateGrassColor(e) {
    props.setAttributes({ grassColor: e.target.value });
  }

  return (
    <div className="paying-attention-edit-block">
      <TextControl label="Question:" />
      <p>Answers:</p>
      <Flex />
    </div>
  );
}
