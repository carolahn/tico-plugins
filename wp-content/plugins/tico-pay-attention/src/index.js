import "./index.scss";
import { Flex, FlexBlock, FlexItem, Button, Icon, TextControl, PanelBody, PanelRow, ColorPicker } from "@wordpress/components";
import { InspectorControls, BlockControls, AlignmentToolbar } from "@wordpress/block-editor";
import { ChromePicker } from "react-color";
// alert("hello from js file");

// prettier-ignore
function ourStartFunction() {
  let locked = false;

  wp.data.subscribe(() => {
    const results = wp.data.select("core/block-editor").getBlocks().filter((block) => (block.name == "ticoplugin/tico-pay-attention" && block.attributes.correctAnswer == undefined));
    if (results.length && locked == false) {
      locked = true;
      wp.data.dispatch("core/editor").lockPostSaving("noanswer");
    }
    if (!results.length && locked) {
      locked = false;
      wp.data.dispatch("core/editor").unlockPostSaving("noanswer");
    }
  });
}

ourStartFunction();

// prettier-ignore
wp.blocks.registerBlockType("ticoplugin/tico-pay-attention", {
  title: "Are You Paying Attention?",
  icon: "smiley",
  category: "common",
  attributes: {
    question: { type: "string" },
    answers: { type: "array", default: [""] },
    correctAnswer: { type: "number", default: undefined },
    bgColor: { type: "string", default: "#ebebeb" },
    theAlignment: { type: "string", default: "left"}
  },
  description: "Thats the block's description.",
  example: {
    attributes: {
      question: "What is the sky color?",
      answers: ['green', 'blue', 'yellow'],
      theAlignment: "center",
      bgColor: "#cfe8f1"
    }
  },
  edit: EditComponent,
  save: (props) => { return null },
});

// prettier-ignore
function EditComponent(props) {
  function updateQuestion(value) {
    props.setAttributes({ question: value });
  }

  function deleteAnswer(indexToDelete) {
    const newAnswers = props.attributes.answers.filter((item, index) => index != indexToDelete);
    props.setAttributes({answers: newAnswers});

    if (indexToDelete == props.attributes.correctAnswer) {
      props.setAttributes({correctAnswer: undefined});
    }
  }

  function markAsCorrect(index) {
      props.setAttributes({correctAnswer: index});
  }

  return (
    <div className="paying-attention-edit-block" style={{backgroundColor: props.attributes.bgColor}}>
      <BlockControls>
        <AlignmentToolbar value={props.attributes.theAlignment} onChange={(x) => props.setAttributes({theAlignment: x})} />
      </BlockControls>
      <InspectorControls>
        <PanelBody title="Background Color" initialOpen={true}>
          <PanelRow>
            <ChromePicker color={props.attributes.bgColor} onChangeComplete={(x) => props.setAttributes({bgColor: x.hex})} disableAlpha={true} />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <TextControl label="Question:" style={{ fontSize: "10px" }} value={props.attributes.question} onChange={updateQuestion} />
        <p style={{ fontSize: "13px", margin: "20px 0 8px 0" }}>Answers:</p>
        {props.attributes.answers.map((answer, index) => (
          <Flex>
            <FlexBlock>
              <TextControl autoFocus={answer == undefined} value={answer} onChange={(newValue) => {
                const newAnswers = props.attributes.answers.concat([]);
                newAnswers[index] = newValue;
                props.setAttributes({answers: newAnswers});
              }} />
            </FlexBlock>
            <FlexItem>
              <Button onClick={() => markAsCorrect(index)} >
                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} />
              </Button>
            </FlexItem>
            <FlexItem>
              <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)} >
                Delete
              </Button>
            </FlexItem>
          </Flex>
      ))}
      <Button isPrimary onClick={() => {
        props.setAttributes({answers: props.attributes.answers.concat([undefined])});
      }}>Add another answer</Button>
    </div>
  );
}
