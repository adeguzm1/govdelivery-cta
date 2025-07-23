import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Tooltip, SelectControl, Flex, FlexItem } from '@wordpress/components';

registerBlockType('govdelivery/cta', {
  title: __('GovDelivery CTA', 'govdelivery-cta'),
  description: __('Call-to-action block for GovDelivery subscriptions.', 'govdelivery-cta'),
  icon: 'megaphone',
  category: 'widgets',
  attributes: {
    title: { type: 'string', default: 'Stay Informed' },
    description: { type: 'string', default: 'Get email updates from our programs and services.' },
    buttonText: { type: 'string', default: 'Subscribe' },
    actionType: { type: 'string', default: 'account' },
    topicId: { type: 'string', default: '' },
  },
  edit: ({ attributes, setAttributes }) => {
    const { title, description, buttonText, actionType, topicId } = attributes;
    const blockProps = useBlockProps();

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('GovDelivery Settings', 'govdelivery-cta')}>
            <SelectControl
              label={__('Action Type', 'govdelivery-cta')}
              value={actionType}
              options={[
                { label: __('Link to list of topics', 'govdelivery-cta'), value: 'account' },
                { label: __('Auto-Subscribe to Topic ID', 'govdelivery-cta'), value: 'topic' },
              ]}
              onChange={(value) => setAttributes({ actionType: value })}
            />
            {actionType === 'topic' && (
              <Flex>
                <FlexItem style={{ flexGrow: 1 }}>
                  <TextControl
                    label={__('GovDelivery Topic ID', 'govdelivery-cta')}
                    value={topicId}
                    onChange={(val) => setAttributes({ topicId: val })}
                    help={__('For example: CASAND_1', 'govdelivery-cta')}
                  />
                </FlexItem>
                <FlexItem>
                  <Tooltip text={__('This ID subscribes the visitor to a specific GovDelivery topic.', 'govdelivery-cta')}>
                    <span
                      className="dashicons dashicons-info-outline"
                      style={{ marginTop: '36px', marginLeft: '6px', color: '#666' }}
                    />
                  </Tooltip>
                </FlexItem>
              </Flex>
            )}
          </PanelBody>
        </InspectorControls>

        <div {...blockProps}>
          <TextControl
            label={__('CTA Title', 'govdelivery-cta')}
            value={title}
            onChange={(val) => setAttributes({ title: val })}
          />
          <TextControl
            label={__('CTA Description', 'govdelivery-cta')}
            value={description}
            onChange={(val) => setAttributes({ description: val })}
          />
          <TextControl
            label={__('Button Text', 'govdelivery-cta')}
            value={buttonText}
            onChange={(val) => setAttributes({ buttonText: val })}
          />
        </div>
      </>
    );
  },
  save: () => {
    // Dynamic block – front-end rendering handled in PHP
    return null;
  },
});
